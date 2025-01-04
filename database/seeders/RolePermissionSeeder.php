<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{

    public function run(): void
    {
        $role_admin = Role::updateOrCreate(['name' => 'admin']);
        $role_owner = Role::updateOrCreate(['name' => 'owner']);
        

        ////////////////////////////////////////////////////////////////////////////

        $permission = Permission::updateOrCreate(['name' => 'crud']);
        $permission2 = Permission::updateOrCreate(['name' => 'view']);
        $permission3 = Permission::updateOrCreate(['name' => 'persetujuan']);

        ////////////////////////////////////////////////////////////////////////////

        $role_admin -> givePermissionTo($permission);
        $role_admin -> givePermissionTo($permission2);

        $role_owner -> givePermissionTo($permission2);
        $role_owner -> givePermissionTo($permission3);

        

        ////////////////////////////////////////////////////////////////////////////

        // $user  = User::find(1); //yg ada pada table user nomer 1
        // $user2 = User::find(2);
        // $user3 = User::find(8);

        // $user->assignRole('owner');
        // $user2->assignRole('admin');
        // $user3->assignRole('admin');
    }
}