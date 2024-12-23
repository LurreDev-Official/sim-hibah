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
        Permission::create(['name' => 'dashboard']);       // Kelola User
        // Permission::create(['name' => 'manage proposals']);   // Kelola Proposal
        // Permission::create(['name' => 'manage outputs']);     // Kelola Luaran

        // Create roles and assign created permissions

        // Admin Role: Full permissions
      
        // $adminRole->givePermissionTo(
        //     [
        //         'dashboard'
        // ]);

       // Membuat permissions (jika belum ada)
$permissions = [
    'dashboard',
    // Tambahkan permission lain sesuai kebutuhan
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
}
        // Kepala LPPM Role: Limited permissions
        $kepalaLPPMRole = Role::firstOrCreate(['name' => 'Kepala LPPM']);
        $kepalaLPPMRole->givePermissionTo([
            'dashboard',
        
        ]);
        // Dosen Role: Restricted permissions
        $dosenRole = Role::firstOrCreate(['name' => 'Dosen']);
        $dosenRole->givePermissionTo( [
            'dashboard'
    ]); 
    
    // Memberikan akses dashboard ke Dosen
        
        // Reviewer Role: Dapat menambahkan permission lain jika diperlukan
        $reviewerRole = Role::firstOrCreate(['name' => 'Reviewer']);
        $reviewerRole->givePermissionTo( [
            'dashboard'
    ]); // Memberikan akses dashboard ke Reviewer
        
        // Create users and assign roles
        $kepalaLPPM = User::firstOrCreate([
            'name' => 'Kepala LPPM User',
            'email' => 'kepalalppm@unhasy.ac.id',
            'password' => bcrypt('kepalalppm@unhasy.ac.id'),
        ]);
        $kepalaLPPM->assignRole('Kepala LPPM');
        
        $dosen = User::firstOrCreate([
            'name' => 'Dosen User',
            'email' => 'dosen@unhasy.ac.id',
            'password' => bcrypt('dosen@unhasy.ac.id'),
        ]);
        $dosen->assignRole('Dosen');
        
        for ($i = 1; $i <= 5; $i++) {
            // Buat data user reviewer dengan email yang berbeda
            $reviewer = User::firstOrCreate([
                'name' => 'Reviewer User ' . $i, // Menambahkan nomor urut pada nama
                'email' => 'reviewer' . $i . '@unhasy.ac.id', // Membuat email unik untuk setiap user
            ], [
                'password' => bcrypt('reviewer'.$i.'@unhasy.ac.id'), // Password berbeda untuk setiap user (contoh: password1, password2, dst.)
            ]);
        
            // Berikan peran 'Reviewer' untuk setiap user
            $reviewer->assignRole('Reviewer');
        }

        $this->call([
            KriteriaPenilaianSeeder::class,
        ]);

       


    }


    
}
