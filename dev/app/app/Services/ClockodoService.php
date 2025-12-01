<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ClockodoService
{
    protected $setting;
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://my.clockodo.com/api/',
            'headers' => [
                'X-ClockodoApiUser' => config('global.CLOCKODO_USER_EMAIL', ''),
                'X-ClockodoApiKey' => config('global.CLOCKODO_API_KEY', ''),
                'X-Clockodo-External-Application' => config('global.CLOCKODO_COMPANY', '') . ';' .
                    config('global.CLOCKODO_USER_EMAIL', ''),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function addTimeLog(array $data)
    {
        if (! $this->client) {
            return ['error' => 'An error occurred while adding the time log'];
        }

        try {
            $response = $this->client->post('v2/entries', [
                'json' => $data
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return json_decode($e->getResponse()->getBody()->getContents(), true);
            }

            return ['error' => 'An error occurred while adding the time log'];
        }
    }

    public function getServices()
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->get('v2/services');
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['services'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function getService(int $serviceId)
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->get('v2/services/' . $serviceId);
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['service'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function deleteLogById(int $id)
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->delete('v2/entries/' . $id);
            $response = json_decode($response->getBody()->getContents(), true);

            return (bool) ($response['success'] ?? false);
        } catch (RequestException $e) {
        }

        return [];
    }

    public function getCustomer(int $customerId)
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->get('v2/customers/' . $customerId);
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['customer'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function getCustomers()
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->get('v2/customers');
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['customers'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function saveCustomer(array $data)
    {
        if (! $this->client) {
            return [];
        }

        $id = $data['id'] ?? null;

        if ($id) {
            unset($data['id']);
        }

        try {
            $response = $this->client->{($id ? 'put' : 'post')}('v2/customers' . ($id ? '/' . $id : ''), [
                'json' => $data,
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['customer'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function getUsers()
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->get('v2/users');
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['users'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    /**
     * Retrieves time logs for a specific Clockodo user within an optional time range.
     *
     * @param int $clockoDoUserId The ID of the Clockodo user.
     * @param string|null $timeSince Optional. The start time for the time logs in ISO 8601 format. Default is null.
     * @param string|null $timeUntil Optional. The end time for the time logs in ISO 8601 format. Default is null.
     *
     * @return array The time logs for the specified user within the given time range.
     */
    public function getTimeLogs(int $clockoDoUserId, ?string $timeSince = null, ?string $timeUntil = null)
    {
        if (! $this->client) {
            return [];
        }

        $startTime = date('Y-m-d H:i:s');
        $timeSince = $timeSince ? date('Y-m-d\TH:i:s\Z', strtotime($timeSince)) : date('Y-m-d\TH:i:s\Z', strtotime($startTime . ' - 6 months'));
        $timeUntil = $timeUntil ? date('Y-m-d\TH:i:s\Z', strtotime($timeUntil)) : date('Y-m-d\TH:i:s\Z', strtotime($startTime . ' + 2 days'));

        try {
            $response = $this->client->get('v2/entries', [
                'query' => [
                    'filter[users_id]' => $clockoDoUserId,
                    'time_since' => $timeSince,
                    'time_until' => $timeUntil,
                ]
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['entries'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function saveUser(array $data)
    {
        if (! $this->client) {
            return [];
        }

        $id = $data['id'] ?? null;

        if ($id) {
            unset($data['id']);
        }

        try {
            $response = $this->client->{($id ? 'put' : 'post')}('v2/users' . ($id ? '/' . $id : ''), [
                'json' => $data,
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['user'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function getProjects(int $customerId)
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->get('v2/projects', [
                'query' => [
                    'filter[customers_id]' => $customerId,
                    'filter[active]' => true,
                ]
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['projects'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    public function getProject(int $projectId)
    {
        if (! $this->client) {
            return [];
        }

        try {
            $response = $this->client->get('v2/projects/' . $projectId);
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['project'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }

    /**
     * Retrieves user reports from the Clockodo API.
     *
     * This method sends a GET request to the Clockodo API endpoint for user reports.
     * It returns an array of user reports based on the provided parameters.
     *
     * @param int $userId The user ID to retrieve reports for.
     * @param array $params Optional parameters to filter the user reports. Possible keys include:
     *                      - 'users_id' (int): Filter by user ID.
     *                      - 'customers_id' (int): Filter by customer ID.
     *                      - 'projects_id' (int): Filter by project ID.
     *                      - 'services_id' (int): Filter by service ID.
     *                      - 'billable' (bool): Filter by billable status.
     *                      - 'billed' (bool): Filter by billed status.
     *                      - 'texts' (string): Filter by text.
     *                      - 'time_since' (string): Filter by start time (ISO 8601 format).
     *                      - 'time_until' (string): Filter by end time (ISO 8601 format).
     *                      - 'time_last_change_since' (string): Filter by last change start time (ISO 8601 format).
     *                      - 'time_last_change_until' (string): Filter by last change end time (ISO 8601 format).
     *                      - 'limit' (int): Limit the number of results.
     *                      - 'offset' (int): Offset the results.
     *
     * @return array An array of user reports. If the request fails or no client is set, an empty array is returned.
     */
    public function getUserReports($userId, array $params = [], &$error = '')
    {
        if (! $this->client) {
            return [];
        }

        $response = $this->client->get('userreports/' . $userId, [
            'query' => $params
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        return $response['userreport'] ?? [];
    }

    /**
     * Retrieves work time reports for a specified user within a date range.
     *
     * @param string $date_since The start date for the report in 'YYYY-MM-DD' format.
     * @param string $date_until The end date for the report in 'YYYY-MM-DD' format.
     * @param int $users_id The ID of the user for whom the report is generated.
     *
     * @return mixed The work time reports for the specified user and date range.
     */
    public function getWorkTimeReports(string $date_since, string $date_until, int $users_id)
    {
        if (! $this->client) {
            return [];
        }

        $params = [
            'date_since' => $date_since,
            'date_until' => $date_until,
            'users_id' => $users_id,
        ];

        try {
            $response = $this->client->get('v2/workTimes', [
                'query' => $params
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['work_time_days'] ?? [];
        } catch (RequestException $e) {
        }

        return [];
    }
}
