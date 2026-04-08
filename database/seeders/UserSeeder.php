<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama (opsional, agar tidak duplikat)
        DB::table('users')->where('email', 'admin@gmail.com')->delete();
        DB::table('users')->where('email', 'operator@gmail.com')->delete();
        DB::table('users')->where('email', 'korwil@gmail.com')->delete();

        // Data users
        $users = [
            [
                'name' => 'Admin Dinas',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin_dinas',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Operator Sekolah',
                'email' => 'operator@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'operator_sekolah',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Korwil',
                'email' => 'korwil@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'korwil',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        $this->command->info('✅ User berhasil di-seed!');
        $this->command->info('📧 Email: admin@gmail.com, operator@gmail.com, korwil@gmail.com');
        $this->command->info('🔑 Password: password123');
    }
}
