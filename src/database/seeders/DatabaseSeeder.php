<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            CreateAdminUserSeeder::class,
            CompanySeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
