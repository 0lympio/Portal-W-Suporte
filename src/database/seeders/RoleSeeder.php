<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Editor']);
        Role::create(['name' => 'Autor']);
        Role::create(['name' => 'Operador']);
        Role::create(['name' => 'Colaborador']);
    }
}
