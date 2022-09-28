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
        //View Modules
        Permission::create(['name' => 'viewCompanies','guard_name' => 'web']);
        Permission::create(['name' => 'viewUsers','guard_name' => 'web']);
        Permission::create(['name' => 'viewContracts','guard_name' => 'web']);
        Permission::create(['name' => 'viewRoles','guard_name' => 'web']);
        Permission::create(['name' => 'viewPermissions','guard_name' => 'web']);
        Permission::create(['name' => 'viewCustomers','guard_name' => 'web']);
        Permission::create(['name' => 'viewServices','guard_name' => 'web']);

        //Company permissions
        Permission::create(['name' => 'company.deleted','guard_name' => 'web']);
        Permission::create(['name' => 'company.filter','guard_name' => 'web']);
        Permission::create(['name' => 'company.create','guard_name' => 'web']);
        Permission::create(['name' => 'company.download','guard_name' => 'web']);
        Permission::create(['name' => 'company.show','guard_name' => 'web']);
        Permission::create(['name' => 'company.edit','guard_name' => 'web']);
        Permission::create(['name' => 'company.delete','guard_name' => 'web']);
        Permission::create(['name' => 'company.restore','guard_name' => 'web']);
        Permission::create(['name' => 'company.forceDelete','guard_name' => 'web']);
        
        //User permissions
        Permission::create(['name' => 'user.deleted','guard_name' => 'web']);
        Permission::create(['name' => 'user.filter','guard_name' => 'web']);
        Permission::create(['name' => 'user.create','guard_name' => 'web']);
        Permission::create(['name' => 'user.download','guard_name' => 'web']);
        Permission::create(['name' => 'user.show','guard_name' => 'web']);
        Permission::create(['name' => 'user.edit','guard_name' => 'web']);
        Permission::create(['name' => 'user.delete','guard_name' => 'web']);
        Permission::create(['name' => 'user.restore','guard_name' => 'web']);
        Permission::create(['name' => 'user.forceDelete','guard_name' => 'web']);
        Permission::create(['name' => 'user.addRoles','guard_name' => 'web']);
        Permission::create(['name' => 'user.removeRoles','guard_name' => 'web']);
        Permission::create(['name' => 'user.addDirectPermissions','guard_name' => 'web']);
        Permission::create(['name' => 'user.removeDirectPermissions','guard_name' => 'web']);

        //Roles Permissions
        Permission::create(['name' => 'role.deleted','guard_name' => 'web']);
        Permission::create(['name' => 'role.filter','guard_name' => 'web']);
        Permission::create(['name' => 'role.create','guard_name' => 'web']);
        Permission::create(['name' => 'role.download','guard_name' => 'web']);
        Permission::create(['name' => 'role.show','guard_name' => 'web']);
        Permission::create(['name' => 'role.edit','guard_name' => 'web']);
        Permission::create(['name' => 'role.delete','guard_name' => 'web']);
        Permission::create(['name' => 'role.restore','guard_name' => 'web']);
        Permission::create(['name' => 'role.forceDelete','guard_name' => 'web']);
        Permission::create(['name' => 'role.addPermissions','guard_name' => 'web']);
        Permission::create(['name' => 'role.removePermissions','guard_name' => 'web']);

        //Contract Permissions
        Permission::create(['name' => 'contract.deleted','guard_name' => 'web']);
        Permission::create(['name' => 'contract.filter','guard_name' => 'web']);
        Permission::create(['name' => 'contract.create','guard_name' => 'web']);
        Permission::create(['name' => 'contract.download','guard_name' => 'web']);
        Permission::create(['name' => 'contract.show','guard_name' => 'web']);
        Permission::create(['name' => 'contract.edit','guard_name' => 'web']);
        Permission::create(['name' => 'contract.delete','guard_name' => 'web']);
        Permission::create(['name' => 'contract.restore','guard_name' => 'web']);
        Permission::create(['name' => 'contract.forceDelete','guard_name' => 'web']);
        Permission::create(['name' => 'contract.addUsers','guard_name' => 'web']);
        Permission::create(['name' => 'contract.removeUsers','guard_name' => 'web']);

        //Customer Permissions
        Permission::create(['name' => 'customer.deleted','guard_name' => 'web']);
        Permission::create(['name' => 'customer.filter','guard_name' => 'web']);
        Permission::create(['name' => 'customer.create','guard_name' => 'web']);
        Permission::create(['name' => 'customer.download','guard_name' => 'web']);
        Permission::create(['name' => 'customer.show','guard_name' => 'web']);
        Permission::create(['name' => 'customer.edit','guard_name' => 'web']);
        Permission::create(['name' => 'customer.delete','guard_name' => 'web']);
        Permission::create(['name' => 'customer.restore','guard_name' => 'web']);
        Permission::create(['name' => 'customer.forceDelete','guard_name' => 'web']);

        //Service Permissions
        Permission::create(['name' => 'service.deleted','guard_name' => 'web']);
        Permission::create(['name' => 'service.filter','guard_name' => 'web']);
        Permission::create(['name' => 'service.create','guard_name' => 'web']);
        Permission::create(['name' => 'service.download','guard_name' => 'web']);
        Permission::create(['name' => 'service.show','guard_name' => 'web']);
        Permission::create(['name' => 'service.edit','guard_name' => 'web']);
        Permission::create(['name' => 'service.delete','guard_name' => 'web']);
        Permission::create(['name' => 'service.restore','guard_name' => 'web']);
        Permission::create(['name' => 'service.forceDelete','guard_name' => 'web']);
        


    }
}
