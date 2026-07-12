<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CounselorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('counselors')->insert([
            [
                'name' => 'Administrator',
                'email' => 'admin',
                'password' => Hash::make('admistrator'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin',
                'password' => Hash::make('admistrator'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Google',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admistrator'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karyawan Konseling',
                'email' => 'karyawan@mentalhealth.test',
                'password' => Hash::make('Karyawan12345'),
                'role' => 'karyawan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}