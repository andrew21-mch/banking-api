<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'super']);
        Permission::create(['name' => 'admin']);
        Permission::create(['name' => 'employee']);
        Permission::create(['name' => 'customer']);

        Role::create(['name' => 'super'])->givePermissionTo('super', 'admin', 'employee', 'customer');
        Role::create(['name' => 'admin'])->givePermissionTo('admin', 'employee', 'customer');
        Role::create(['name' => 'employee'])->givePermissionTo('employee', 'customer');
        Role::create(['name' => 'customer'])->givePermissionTo('customer');

    }
}
