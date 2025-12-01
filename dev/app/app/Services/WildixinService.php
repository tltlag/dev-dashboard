<?php

namespace App\Services;

use Exception;
use Wildix\Integrations\Client;
use App\Models\User;
use App\Models\BexioEmployee;
use App\Models\CallHistory;
use Carbon\Carbon;

class WildixinService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'host' => config('global.WILDIXIN_HOST', null),
            'app_id' => config('global.WILDIXIN_APP_ID', null),
            'secret_key' => config('global.WILDIXIN_SECRET_KEY', null),
            'app_name' => config('global.WILDIXIN_APP_NAME', null),
        ], []);
    }

    public function getColleagueById($wildixinUserId)
    {
        $response = $this->client->get('api/v1/Colleagues/' . $wildixinUserId);

        if ($response->getStatusCode() !== 200) {
            throw new Exception(__('Unable to find user.'));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (! (isset($data['result']) && $data['result'])) {
            throw new Exception(__('Unable to find user.'));
        }

        return [
            'name' => $data['result']['name'],
            'email' => $data['result']['email'],
            'phone' => $data['result']['mobilePhone'] ? $data['result']['mobilePhone'] : $data['result']['officePhone'],
            'wildixin_id' => $data['result']['id'],
            'wildixin_response' => json_encode($data['result']),
        ];
    }

    public function getColleagueByExtensionId($wildixinExtensionId)
    {
        $response = $this->client->get('api/v1/PBX/Colleagues/', [
            'params' => [
                'searchFields' => 'extension',
                'search' => $wildixinExtensionId,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new Exception(__('Unable to find user.'));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (! (isset($data['result'], $data['result']['records']) && $data['result'] && $data['result']['records'])) {
            throw new Exception(__('Unable to find user.'));
        }

        $data = reset($data['result']['records']);

        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['mobilePhone'] ? $data['mobilePhone'] : $data['officePhone'],
            'wildixin_id' => $data['id'],
            'wildixin_response' => json_encode($data),
        ];
    }

    public function getCallHistory($wildixinExtensionId, $options = [])
    {
        $response = $this->client->get('api/v1/User/' . $wildixinExtensionId . '/CallHistory', $options);

        if ($response->getStatusCode() !== 200) {
            throw new Exception(__('Unable to find call history.'));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (! (isset($data['result'], $data['result']['records']) && $data['result'] && $data['result']['records'])) {
            throw new Exception(__('Unable to find call history.'));
        }

        return $data['result'];
    }

    public function getCallHistoryById($wildixinCallHistoryId)
    {
        $response = $this->client->get('api/v1/CallHistory/' . $wildixinCallHistoryId);

        if ($response->getStatusCode() !== 200) {
            throw new Exception(__('Unable to find call history.'));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (! (isset($data['result']) && $data['result'])) {
            throw new Exception(__('Unable to find call history.'));
        }

        return $data['result'];
    }

    public function deleteCallHistoryById($wildixinCallHistoryId)
    {
        $response = $this->client->delete('api/v1/CallHistory/' . $wildixinCallHistoryId);

        if ($response->getStatusCode() !== 200) {
            throw new Exception(__('Unable to delete call history.'));
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (! (isset($data['result']) && $data['result'])) {
            throw new Exception(__('Unable to delete call history.'));
        }

        return true;
    }

    public function syncCallHistory($empId = null)
    {
        set_time_limit(0);

        if (! empty($empId)) {
            $employees = User::where("id", $empId)->get();
        } else {
            $employees = User::all();
        }

        if ($employees->count() <= 0) {
            return true;
        }

        foreach ($employees as $employee) {
            try {
                $totalRecords = CallHistory::where([
                    'extension_id' => $employee->extension_number,
                    'user_id' => $employee->id,
                ])->count();

                $params = [
                    'start' => $totalRecords > 0 ? ($totalRecords - 1) : 0,
                    'count' => 100,
                    'sort' => 'id',
                    'dir' => 'asc',
                ];

                $histories = $this->getCallHistory($employee->extension_number, [
                    'params' => $params,
                ]);

                while ($histories) {
                    $bulkInsert = [];

                    foreach ($histories['records'] as $record) {
                        $bulkInsert[] = [
                            'extension_id' => $employee->extension_number,
                            'user_id' => $employee->id,
                            'start' => $record['start'],
                            'end' => $record['end'],
                            'src' => $record['src'],
                            'dst' => $record['dst'],
                            'from_number' => $record['from_number'],
                            'to_number' => $record['to_number'],
                            'to_name' => $record['to_name'],
                            'from_name' => $record['from_name'],
                            'billsec' => $record['billsec'],
                            'lastapp' => $record['lastapp'],
                            'dest_type' => $record['dest_type'],
                            'disposition' => $record['disposition'],
                            'wildixin_response' => json_encode($record),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }

                    CallHistory::upsert(
                        $bulkInsert,
                        [
                            'extension_id',
                            'user_id',
                            'start',
                        ],
                        [
                            'end',
                            'src',
                            'dst',
                            'from_number',
                            'to_number',
                            'to_name',
                            'from_name',
                            'billsec',
                            'lastapp',
                            'dest_type',
                            'disposition',
                            'wildixin_response',
                            'created_at',
                            'updated_at',
                        ]
                    );

                    $params['start'] += count($bulkInsert);
                    $histories = $this->getCallHistory($employee->extension_number, [
                        'params' => $params,
                    ]);
                }
            } catch (Exception $e) {
                $history = [];
            }
        }

        return true;
    }
}
