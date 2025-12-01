<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Intreation Dashboard',
            'username' => 'admin',
            'email' => 'idash@yopmail.com',
            'password' => bcrypt('t4XbbLP@tjuZ09F0'),
            'role' => Admin::ROLE_TYPE_SUPER_ADMIN,
            'status' => Admin::STATUS_ACTIVE,
        ]);
    }
}
