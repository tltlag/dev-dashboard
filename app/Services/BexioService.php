<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\BexioEmployee;
use App\Models\BexioEmployeeHasCompany;
use GuzzleHttp\Exception\BadResponseException;

class BexioService
{
    protected $client;
    protected $headers;

    public function __construct()
    {
        $this->headers = array(
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('global.BEXIO_TOKEN', ''),
            'Content-Type' => 'application/json',
        );
        $this->client = new Client();
    }

    public function getContactByPhoneNumber($phoneNumber)
    {
        try {
            $response = $this->client->request(
                'POST',
                'https://api.bexio.com/2.0/contact/search',
                [
                    'headers' => $this->headers,
                    'body' => '[
                        {
                            "field": "phone_fixed",
                            "value": "' . $phoneNumber . '",
                            "criteria": "like"
                        }
                    ]',
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            if (!$records) {
                $response = $this->client->request(
                    'POST',
                    'https://api.bexio.com/2.0/contact/search',
                    [
                        'headers' => $this->headers,
                        'body' => '[
                            {
                                "field": "phone_mobile",
                                "value": "' . $phoneNumber . '",
                                "criteria": "like"
                            }
                        ]',
                    ]
                );

                $records = json_decode($response->getBody()->getContents(), true);
            }

            return $records ? reset($records) : [];
        } catch (BadResponseException $e) {
        }

        return [];
    }

    public function getServices($phoneNumber = '')
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.bexio.com/2.0/client_service',
                [
                    'headers' => $this->headers,
                    'body' => '[
                        {
                            "field": "phone_mobile",
                            "value": "' . $phoneNumber . '",
                            "criteria": "like"
                        }
                    ]',
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return $records;
        } catch (BadResponseException $e) {
        }

        return [];
    }

    public function getContacts()
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.bexio.com/2.0/contact',
                [
                    'headers' => $this->headers,
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return $records;
        } catch (BadResponseException $e) {
        }

        return [];
    }

    public function getContactRelations()
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.bexio.com/2.0/contact_relation',
                [
                    'headers' => $this->headers,
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return $records;
        } catch (BadResponseException $e) {
        }

        return [];
    }

    public function syncContacts(
        bool $syncCallHistory = false,
        ?WildixinService $wildixinService = null,
        ClockodoService $clockodoService,
        ?int $empId = null
    ) {
        $contacts = $this->getContacts();
      
        $customers = $clockodoService->getCustomers();
        $customers = $customers ? collect($customers)->pluck('name', 'id')->toArray() : [];
        $employees = $clockodoService->getUsers();
        $employees = $employees ? collect($employees)->pluck('email', 'id')->toArray() : [];

        if (!$contacts) {
            return false;
        }

        BexioEmployee::truncate();
        BexioEmployeeHasCompany::truncate();

        foreach ($contacts as $contact) {
            $bexioEmployee = BexioEmployee::where("emp_id", $contact['id'])->first();
            $clockoDoId = null;

            if (! ($bexioEmployee && $bexioEmployee->clockodo_emp_id)) {
                if ($contact['contact_type_id'] == BexioEmployee::CONTACT_TYPE_COMPANY) {
                    $clockoDoId = array_search(trim($contact['name_2'] . ' ' . $contact['name_1']), $customers);

                    if (! $clockoDoId) {
                        $clockodoCustomer = $clockodoService->saveCustomer([
                            'name' => trim($contact['name_2'] . ' ' . $contact['name_1']),
                        ]);

                        if ($clockodoCustomer) {
                            $clockoDoId = $clockodoCustomer['id'];
                        }
                    }
                } else {
                    if ($contact['mail']) {
                        $clockoDoId = array_search($contact['mail'], $employees);

                        if (! $clockoDoId) {
                            $clockodoEmp = $clockodoService->saveUser([
                                'name' => trim($contact['name_2'] . ' ' . $contact['name_1']),
                                'email' => $contact['mail'],
                                'role' => 'worker',
                            ]);

                            if ($clockodoEmp) {
                                $clockoDoId = $clockodoEmp['id'];
                            }
                        }
                    }
                }
            } else {
                if ($contact['contact_type_id'] == BexioEmployee::CONTACT_TYPE_COMPANY) {
                    $clockodoCustomer = $clockodoService->saveCustomer([
                        'contact_id' => $bexioEmployee->clockodo_emp_id,
                        'name' => trim($contact['name_2'] . ' ' . $contact['name_1']),
                    ]);
                } else {
                    $clockodoEmp = $clockodoService->saveUser([
                        'id' => $bexioEmployee->clockodo_emp_id,
                        'name' => trim($contact['name_2'] . ' ' . $contact['name_1']),
                        'email' => $contact['mail'],
                        'role' => 'worker',
                    ]);
                }
            }

            if (!$bexioEmployee instanceof BexioEmployee) {
                BexioEmployee::insert([
                    'emp_id' => $contact['id'],
                    'name' => trim($contact['name_2'] . ' ' . $contact['name_1']),
                    'first_name' => $contact['name_2'],
                    'last_name' => $contact['name_1'],
                    'phone_number' => $contact['phone_fixed'],
                    'mobile_number' => $contact['phone_mobile'],
                    'contact_type' => $contact['contact_type_id'],
                    'fax_number' => $contact['fax'],
                    'email' => $contact['mail'],
                    'city' => $contact['city'],
                    'postal_code' => $contact['postcode'],
                    'bexio_country_id' => $contact['country_id'],
                    'bexio_response' => json_encode($contact),
                    'clockodo_emp_id' => $clockoDoId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                $bexioEmployee->emp_id = $contact['id'];
                $bexioEmployee->name = trim($contact['name_2'] . ' ' . $contact['name_1']);
                $bexioEmployee->first_name = $contact['name_2'];
                $bexioEmployee->last_name = $contact['name_1'];
                $bexioEmployee->phone_number = $contact['phone_fixed'];
                $bexioEmployee->mobile_number = $contact['phone_mobile'];
                $bexioEmployee->fax_number = $contact['fax'];
                $bexioEmployee->contact_type = $contact['contact_type_id'];
                $bexioEmployee->email = $contact['mail'];
                $bexioEmployee->city = $contact['city'];
                $bexioEmployee->postal_code = $contact['postcode'];
                $bexioEmployee->bexio_country_id = $contact['country_id'];
                $bexioEmployee->bexio_response = json_encode($contact);

                if (! $bexioEmployee->clockodo_emp_id) {
                    $bexioEmployee->clockodo_emp_id = $clockoDoId;
                }

                $bexioEmployee->updated_at = Carbon::now();
                $bexioEmployee->save();
            }
        }

        $empId = $empId ? $empId : (auth('employee')->user()->id ?? null);

        if ($syncCallHistory && $wildixinService instanceof WildixinService && $empId) {
            $wildixinService->syncCallHistory($empId);
        }

        return $this->contactRelations();
    }

    protected function contactRelations()
    {
        $contactRelations =  $this->getContactRelations();

        if (!$contactRelations) {
            return false;
        }

        foreach ($contactRelations as $relation) {
            $empId = $relation['contact_id'];
            $companyId = $relation['contact_sub_id'];
            $contactRelationId = $relation['id'];

            $company = BexioEmployee::where([
                'emp_id' => $companyId,
                'contact_type' => BexioEmployee::CONTACT_TYPE_COMPANY,
            ])->first();

            if (!$company instanceof BexioEmployee) {
                $empId = $relation['contact_sub_id'];
                $companyId = $relation['contact_id'];
            }

            $data = [
                'bexio_employee_id' => $empId,
                'bexio_company_id' => $companyId,
                'contact_relation_id' => $contactRelationId
            ];

            if (!BexioEmployeeHasCompany::where($data)->first()) {
                BexioEmployeeHasCompany::insert(
                    array_merge(
                        $data,
                        [
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]
                    )
                );
            }
        }

        return true;
    }

    public function getOwner()
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.bexio.com/3.0/users/me',
                [
                    'headers' => $this->headers,
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return $records;
        } catch (BadResponseException $e) {
        }

        return [];
    }

    public function saveCustomer(array $data)
    {
        $contactId = $data['contact_id'] ?? null;

        if ($contactId) {
            unset($data['contact_id']);
        }

        try {
            $response = $this->client->request(
                'POST',
                'https://api.bexio.com/2.0/contact' . ($contactId ? '/' . $contactId : ''),
                [
                    'headers' => $this->headers,
                    'body' => json_encode($data),
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return $records;
        } catch (BadResponseException $e) {
            echo ($e->getMessage());
        }

        return [];
    }

    public function getCountries()
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.bexio.com/2.0/country',
                [
                    'headers' => $this->headers,
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return $records;
        } catch (BadResponseException $e) {
        }

        return [];
    }

    public function saveContactRelation(array $data)
    {
        try {
            $contactRelation = BexioEmployeeHasCompany::where([
                'bexio_employee_id' => $data['contact_id'],
                'bexio_company_id' => $data['contact_sub_id'],
            ])
            ->orWhere([
                'bexio_employee_id' => $data['contact_sub_id'],
                'bexio_company_id' => $data['contact_id'],
            ])->first();

            if ($contactRelation instanceof BexioEmployeeHasCompany) {
                $response = $this->client->request(
                    'GET',
                    'https://api.bexio.com/2.0/contact_relation/' . $contactRelation->contact_relation_id,
                    [
                        'headers' => $this->headers,
                    ]
                );

                $records = json_decode($response->getBody()->getContents(), true);

                return $records;
            }

            $response = $this->client->request(
                'POST',
                'https://api.bexio.com/2.0/contact_relation',
                [
                    'headers' => $this->headers,
                    'body' => json_encode($data),
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return $records;
        } catch (BadResponseException $e) {
        }

        return [];
    }

    public function removeContactRelation(array $data)
    {
        try {
            $response = $this->client->request(
                'DELETE',
                'https://api.bexio.com/2.0/contact_relation/' . $data['contact_relation_id'],
                [
                'headers' => array(
                   'Accept' => 'application/json',
                   'Authorization' => 'Bearer ' . config('global.BEXIO_TOKEN', ''),
                )
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return true;
        } catch (BadResponseException $e) {
        }

        return false;
    }

    public function removeContact(int $contactId)
    {
        try {
            $response = $this->client->request(
                'DELETE',
                'https://api.bexio.com/2.0/contact/' . $contactId,
                [
                'headers' => array(
                   'Accept' => 'application/json',
                   'Authorization' => 'Bearer ' . config('global.BEXIO_TOKEN', ''),
                )
                ]
            );

            $records = json_decode($response->getBody()->getContents(), true);

            return (bool) (isset($records['success']) && $records['success']);
        } catch (BadResponseException $e) {
        }

        return false;
    }
}
