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
        Permission::create(['name' => 'admin']);
        Permission::create(['name' => 'employee']);
        Permission::create(['name' => 'user']);

        Role::create(['name' => 'admin'])->givePermissionTo('admin', 'employee', 'user');
        Role::create(['name' => 'employee'])->givePermissionTo('employee', 'user');
        Role::create(['name' => 'user'])->givePermissionTo('user');
        
    }
}
