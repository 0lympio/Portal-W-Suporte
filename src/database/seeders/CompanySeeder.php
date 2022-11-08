<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create(['name' => 'Fulltime', 'description' => null, 'status' => true]);
        Company::create(['name' => 'Sem Parar', 'description' => null, 'status' => true]);
        Company::create(['name' => 'NUMBERONE', 'description' => null, 'status' => true]);
    }
}
