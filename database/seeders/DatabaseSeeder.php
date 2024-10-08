<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage users']);       // Kelola User
        Permission::create(['name' => 'manage proposals']);   // Kelola Proposal
        Permission::create(['name' => 'manage outputs']);     // Kelola Luaran

        // Create roles and assign created permissions

        // Admin Role: Full permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(['manage users', 'manage proposals', 'manage outputs']);

        // Kepala LPPM Role: Limited permissions
        $kepalaLPPMRole = Role::create(['name' => 'Kepala LPPM']);
        $kepalaLPPMRole->givePermissionTo(['manage proposals', 'manage outputs']);

        // Dosen Role: Restricted permissions
        $dosenRole = Role::create(['name' => 'Dosen']);
        $dosenRole->givePermissionTo(['manage proposals']);

        // Create users and assign roles
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Admin');

        $kepalaLPPM = User::create([
            'name' => 'Kepala LPPM User',
            'email' => 'kepalalppm@example.com',
            'password' => bcrypt('kepalalppm@example.com'),
        ]);
        $kepalaLPPM->assignRole('Kepala LPPM');

        $dosen = User::create([
            'name' => 'Dosen User',
            'email' => 'dosen@example.com',
            'password' => bcrypt('dosen@example.com'),
        ]);
        $dosen->assignRole('Dosen');
    }
}
