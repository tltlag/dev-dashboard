<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Services\BexioService;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    public function run(BexioService $bexioService)
    {
        $countries = $bexioService->getCountries();

        foreach ($countries as $country) {
            $countryModel = Country::firstOrNew([
                'bexio_country_id' => $country['id'],
            ]);

            $countryModel->fill([
                'name' => $country['name'],
                'name_short' => $country['name_short'],
                'iso3166_alpha2' => $country['iso_3166_alpha2'],
                'default' => $country['iso_3166_alpha2'] == 'CH' ? true : false,
            ]);

            $countryModel->save();
        }
    }
}
