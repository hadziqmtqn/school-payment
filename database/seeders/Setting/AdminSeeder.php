<?php

namespace Database\Seeders\Setting;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = Role::create([
            'slug' => Str::uuid()->toString(),
            'name' => 'super_admin'
        ]);

        $superadmin->syncPermissions(Permission::all());

        $mainAdmin = new User();
        $mainAdmin->name = 'Super Admin';
        $mainAdmin->email = 'superadmin@bkn.my.id';
        $mainAdmin->password = Hash::make('superadmin');
        $mainAdmin->save();

        $mainAdmin->assignRole('super_admin');

        $admin = new Admin();
        $admin->user_id = $mainAdmin->id;
        $admin->whatsapp_number = '085157088717';
        $admin->save();

        // AdminRole
        Role::create([
            'slug' => Str::uuid()->toString(),
            'name' => 'admin'
        ]);
    }
}
