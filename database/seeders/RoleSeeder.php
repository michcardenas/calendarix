<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['name' => 'Cliente', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
    }
}

