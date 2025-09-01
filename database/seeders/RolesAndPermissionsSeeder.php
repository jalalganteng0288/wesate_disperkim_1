<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $operatorRole = Role::create(['name' => 'Operator']);
        $masyarakatRole = Role::create(['name' => 'Masyarakat']);

        // Anda bisa juga membuat permissions di sini jika dibutuhkan
        // Contoh: Permission::create(['name' => 'edit articles']);

        // Buat user Super Admin
        $user = User::create([
            'name' => 'Admin Disperkim',
            'email' => 'admin@disperkim.test',
            'password' => bcrypt('password123') // Ganti 'password' dengan password yang aman
        ]);
        
        // Berikan role Super Admin ke user tersebut
        $user->assignRole($superAdminRole);
    }
}