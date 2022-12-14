<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'sender_name_management']);
        Permission::create(['name' => 'transactions_management']);
        Permission::create(['name' => 'transaction_customers_management']);
        Permission::create(['name' => 'clients_management']);
        Permission::create(['name' => 'users_management']);
        Permission::create(['name' => 'assign_permissions']);

        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleClient = Role::create(['name' => 'Client']);

        $roleAdmin->givePermissionTo(Permission::all());
        $roleClient->givePermissionTo(['assign_permissions','transaction_customers_management']);
    }
}
