<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'user.viewModule','guard_name' => 'web']);
        Permission::create(['name' => 'user.viewDeleted','guard_name' => 'web']);
        Permission::create(['name' => 'user.filter','guard_name' => 'web']);
        Permission::create(['name' => 'user.create','guard_name' => 'web']);
        Permission::create(['name' => 'user.download','guard_name' => 'web']);
        Permission::create(['name' => 'user.show','guard_name' => 'web']);
        Permission::create(['name' => 'user.edit','guard_name' => 'web']);
        Permission::create(['name' => 'user.delete','guard_name' => 'web']);
        Permission::create(['name' => 'user.restore','guard_name' => 'web']);
        Permission::create(['name' => 'user.forceDelete','guard_name' => 'web']);
        Permission::create(['name' => 'user.addRemoveRoles','guard_name' => 'web']);
        Permission::create(['name' => 'user.addRemoveDirectPermissions','guard_name' => 'web']);

    }
}
