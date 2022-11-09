<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'categories.index']);
        Permission::create(['name' => 'categories.create']);
        Permission::create(['name' => 'categories.edit']);
        Permission::create(['name' => 'categories.destroy']);
        Permission::create(['name' => 'categories.status']);

        Permission::create(['name' => 'roles.index']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.edit']);
        Permission::create(['name' => 'roles.destroy']);

        Permission::create(['name' => 'posts.index']);
        Permission::create(['name' => 'posts.create']);
        Permission::create(['name' => 'posts.edit']);
        Permission::create(['name' => 'posts.destroy']);
        Permission::create(['name' => 'posts.read']);
        Permission::create(['name' => 'posts.status']);

        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'users.store']);
        Permission::create(['name' => 'users.update']);
        Permission::create(['name' => 'users.status']);
        Permission::create(['name' => 'users.destroy']);
        Permission::create(['name' => 'users.changePassword']);
        Permission::create(['name' => 'users.import']);

        Permission::create(['name' => 'slideshow.index']);
        Permission::create(['name' => 'slideshow.addImages']);
        Permission::create(['name' => 'slideshow.displayTime']);
        Permission::create(['name' => 'slideshow.destroy']);

        Permission::create(['name' => 'questionnaires.index']);
        Permission::create(['name' => 'questionnaires.create']);
        Permission::create(['name' => 'questionnaires.edit']);
        Permission::create(['name' => 'questionnaires.destroy']);
        Permission::create(['name' => 'questionnaires.show']);
        Permission::create(['name' => 'questionnaires.status']);

        Permission::create(['name' => 'approvals.index']);
        Permission::create(['name' => 'approvals.approver']);
        Permission::create(['name' => 'approvals.rejected']);

        Permission::create(['name' => 'comments.show']);
        Permission::create(['name' => 'comments.store']);
        Permission::create(['name' => 'comments.destroy']);

        Permission::create(['name' => 'questions.create']);

        Permission::create(['name' => 'uploads.store']);
        Permission::create(['name' => 'users.export']);
        Permission::create(['name' => 'content.show']);

        Permission::create(['name' => 'companies.index']);
        Permission::create(['name' => 'companies.store']);
        Permission::create(['name' => 'companies.edit']);
        Permission::create(['name' => 'companies.destroy']);
    }
}
