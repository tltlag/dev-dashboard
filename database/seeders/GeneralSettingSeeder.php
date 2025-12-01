<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::insert([
            [
                'group' => Configuration::GROUP_TYPE_GENERAL,
                'key' => 'SITE_TITLE',
                'value' => 'Intreation Dashboard',
            ],
            [
                'group' => Configuration::GROUP_TYPE_GENERAL,
                'key' => 'SITE_EMAIL',
                'value' => 'idash@yopmail.com',
            ],
            [
                'group' => Configuration::GROUP_TYPE_GENERAL,
                'key' => 'SITE_PHONE',
                'value' => '+919896554359',
            ],
            [
                'group' => Configuration::GROUP_TYPE_GENERAL,
                'key' => 'SITE_ADDRESS',
                'value' => 'UNIT 502-503, Sector 48, Gurugram',
            ],
            [
                'group' => Configuration::GROUP_TYPE_GENERAL,
                'key' => 'DEFAULT_TIMEZONE',
                'value' => 'UTC',
            ],
            [
                'group' => Configuration::GROUP_TYPE_GENERAL,
                'key' => 'DATE_FORMT',
                'value' => 'l, M d Y',
            ],
            [
                'group' => Configuration::GROUP_TYPE_GENERAL,
                'key' => 'TIME_FORMAT',
                'value' => 'h:i a',
            ],
        ]);
    }
}
