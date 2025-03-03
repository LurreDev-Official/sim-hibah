<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;


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
        $adminlppmRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminlppmRole->givePermissionTo([
            'dashboard',
        ]);
        // Dosen Role: Restricted permissions
        $dosenRole = Role::firstOrCreate(['name' => 'Dosen']);
        $dosenRole->givePermissionTo( [
            'dashboard'
        ]); 
        // Reviewer Role: Dapat menambahkan permission lain jika diperlukan
        $reviewerRole = Role::firstOrCreate(['name' => 'Reviewer']);
        $reviewerRole->givePermissionTo( [
            'dashboard'
        ]); 
        // Memberikan akses dashboard ke Reviewer
        // Create users and assign roles
        $kepalaLPPM = User::firstOrCreate([
            'name' => 'Kepala LPPM User',
            'email' => 'kepalalppm@unhasy.ac.id',
            'password' => bcrypt('kepalalppm@unhasy.ac.id'),
        ]);
        $kepalaLPPM->assignRole('Kepala LPPM');


        $adminlppmRole = User::firstOrCreate([
            'name' => 'Admin LPPM',
            'email' => 'adminlppm@unhasy.ac.id',
            'password' => bcrypt('adminlppm@unhasy.ac.id'),
        ]);
        $adminlppmRole->assignRole('Admin');

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



        //create periodes
        $periodes = [
            [
                'tahun' => 2025,
                'tanggal_awal' => '2025-01-01',
                'tanggal_akhir' => '2025-12-31',
                'nominal' => 1000000,
                'is_active' => true,
            ],
            [
                'tahun' => 2026,
                'tanggal_awal' => '2026-01-01',
                'tanggal_akhir' => '2026-12-31',
                'nominal' => 1000000,
                'is_active' => false,
            ],
            [
                'tahun' => 2027,
                'tanggal_awal' => '2027-01-01',
                'tanggal_akhir' => '2027-12-31',
                'nominal' => 1000000,
                'is_active' => false,
            ],
        ];

        foreach ($periodes as $periode) {
            \App\Models\Periode::create($periode);
        }

        Fakultas::create([
            'name' => 'Fakultas Agama Islam',
            'initial' => 'FAI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Fakultas::create([
            'name' => 'Fakultas Teknik',
            'initial' => 'FT',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Fakultas::create([
            'name' => 'Fakultas Teknologi Informasi',
            'initial' => 'FTI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Fakultas::create([
            'name' => 'Fakultas Ekonomi',
            'initial' => 'FE',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Fakultas::create([
            'name' => 'Fakultas Ilmu Pendidikan',
            'initial' => 'FIP',
            'created_at' => now(),
            'updated_at' => now()
        ]);


        // Fakultas Agama Islam (ID: 1)
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S1 Hukum Keluarga',
            'initial' => 'S1 HK',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => "S1 Hukum Ekonomi Syari'ah",
            'initial' => 'S1 HES',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S1 Manajemen Pendidikan Islam',
            'initial' => 'S1 MPI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S1 Komunikasi dan Penyiaran Islam',
            'initial' => 'S1 KPI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S1 Pendidikan Agama Islam',
            'initial' => 'S1 PAI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S1 Pendidikan Bahasa Arab',
            'initial' => 'S1 PBA',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S1 Pendidikan Guru MI',
            'initial' => 'S1 PGMI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S2 Pendidikan Pendidikan Bahasa Arab',
            'initial' => 'S2 PBA',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S2 Pendidikan Agama Islam',
            'initial' => 'S2 PAI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 1,
            'name' => 'S2 Hukum Keluarga',
            'initial' => 'S2 HK',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Fakultas Teknik (ID: 2)
        Prodi::create([
            'fakultas_id' => 2,
            'name' => 'S1 Teknik Mesin',
            'initial' => 'S1 TM',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 2,
            'name' => 'S1 Teknik Elektro',
            'initial' => 'S1 TE',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 2,
            'name' => 'S1 Teknik Sipil',
            'initial' => 'S1 TS',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 2,
            'name' => 'S1 Teknik Industri',
            'initial' => 'S1 T.ind',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Fakultas Teknologi Informasi (ID: 3)
        Prodi::create([
            'fakultas_id' => 3,
            'name' => 'S1 Teknik Informatika',
            'initial' => 'S1 TI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 3,
            'name' => 'S1 Sistem Informasi',
            'initial' => 'S1 SI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 3,
            'name' => 'S1 Teknologi Informasi',
            'initial' => 'S1 TIF',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Fakultas Ekonomi (ID: 4)
        Prodi::create([
            'fakultas_id' => 4,
            'name' => 'S1 Manajemen',
            'initial' => 'S1 M',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 4,
            'name' => 'S1 Akuntansi',
            'initial' => 'S1 A',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 4,
            'name' => 'S1 Akuntansi',
            'initial' => 'S1 EI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Fakultas Ilmu Pendidikan (ID: 5)
        Prodi::create([
            'fakultas_id' => 5,
            'name' => 'S1 Pendidikan Guru Sekolah Dasar',
            'initial' => 'S1 PGSD',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 5,
            'name' => 'S1 Pendidikan Bahasa dan Sastra Indonesia',
            'initial' => 'S1 PBSI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 5,
            'name' => 'S1 Pendidikan Bahasa Inggris',
            'initial' => 'S1 PBI',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 5,
            'name' => 'S1 Pendidikan IPA',
            'initial' => 'S1 IPA',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 5,
            'name' => 'S1 Pendidikan Matematika',
            'initial' => 'S1 PM',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Prodi::create([
            'fakultas_id' => 5,
            'name' => 'S2 Pendidikan Bahasa dan Sastra Indonesia',
            'initial' => 'S2',
            'created_at' => now(),
            'updated_at' => now()
        ]);

         
    }
}
