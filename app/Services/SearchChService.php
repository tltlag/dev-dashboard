<?php

namespace App\Services;

use App\Models\Country;
use SimpleXmlElement;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class SearchChService
{
    protected $client;
    protected $headers;

    public function __construct()
    {
        $this->headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        );
        $this->client = new Client();
    }

    public function getContacts(string $phoneNumber)
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://tel.search.ch/api/',
                [
                    'headers' => $this->headers,
                    'query' => [
                        'was' => $phoneNumber,
                        'key' => config('global.SEARCHCH_KEY', ''),
                    ]
                ]
            );

            $xml = new SimpleXmlElement($response->getBody()->getContents());
            $found = [];

            if (str_contains($phoneNumber, 'anonymous')) {
                return [];
            } else {
                foreach ($xml->entry as $entry) {
                    $namespaces = $entry->getNameSpaces(true);
                    $telnum = $entry->children($namespaces['tel']);
                    $supportPhoneNumber = '';
                    $email = '';
                    $website = '';

                    foreach ($telnum->extra as $extra) {
                        $attributes = $extra->attributes();
                        switch ((string)$attributes['type']) {
                            case 'Support':
                                $support = (string)$extra;
                                break;
                            case 'email':
                                $email = (string)$extra;
                                break;
                            case 'website':
                                $website = (string)$extra;
                                break;
                        }
                    }

                    $country = $telnum->country->__toString();
                    $street = $telnum->street->__toString();
                    $streetNumber = $telnum->streetno->__toString();
                    $address = trim($street . ",  " . $streetNumber, "\s\,");
                    if ($country) {
                        $country = Country::where('iso3166_alpha2', strtoupper($country))->pluck('bexio_country_id')->first();
                    } else {
                        $country = null;
                    }

                    $found[] = [
                        'name' => $telnum->name->__toString(),
                        'zip' => $telnum->zip->__toString(),
                        'city' => $telnum->city->__toString(),
                        'phone' => $telnum->phone->__toString(),
                        'supportPhoneNumber' => $supportPhoneNumber,
                        'email' => trim($email, '\s\*'),
                        'website' => trim($website),
                        'country' => trim($country),
                        'address' => $address,
                    ];
                }
            }

            return $found;
        } catch (BadResponseException $e) {
        }

        return [];
    }
}
