<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ======================
        // 1. USERS
        // ======================
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin Dinas',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin_dinas',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $operatorId = DB::table('users')->insertGetId([
            'name' => 'Operator Sekolah',
            'email' => 'operator@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'operator_sekolah',
            'created_by' => $adminId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $korwilUserId = DB::table('users')->insertGetId([
            'name' => 'Korwil Waesama',
            'email' => 'korwil@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'korwil',
            'created_by' => $adminId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ======================
        // 2. KORWIL
        // ======================
        $korwilId = DB::table('korwil')->insertGetId([
            'user_id' => $korwilUserId,
            'kode_wilayah' => 'KW001',
            'nama_korwil' => 'Korwil Waesama',
            'wilayah_kerja' => 'Kecamatan Waesama',
            'no_sk' => 'SK-001/2026',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ======================
        // 3. SEKOLAH
        // ======================
        $sekolahId = DB::table('sekolah')->insertGetId([
            'npsn' => '12345678',
            'nama_sekolah' => 'SD Negeri Waesama 1',
            'alamat' => 'Jl. Pendidikan No.1',
            'kelurahan' => 'Waesama',
            'kecamatan' => 'Waesama',
            'id_korwil' => $korwilId,
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ======================
        // 4. USER ASSIGNMENTS
        // ======================
        DB::table('user_assignments')->insert([
            [
                'user_id' => $operatorId,
                'target_type' => 'sekolah',
                'target_id' => $sekolahId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $korwilUserId,
                'target_type' => 'korwil',
                'target_id' => $korwilId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // ======================
        // 5. PERIODE AJARAN
        // ======================
        $periodeId = DB::table('periode_ajaran')->insertGetId([
            'tahun_ajaran' => '2024/2025',
            'semester' => '1',
            'is_active' => true,
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2024-12-31',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ======================
        // 6. ABSENSI
        // ======================
        DB::table('absensi')->insert([
            'id_sekolah' => $sekolahId,
            'id_periode' => $periodeId,
            'tanggal' => now(),

            'jumlah_hadir' => 20,
            'jumlah_sakit' => 2,
            'jumlah_izin' => 1,
            'jumlah_alpha' => 2,
            'total_siswa' => 25,

            'keterangan' => 'Data awal absensi',
            'diinput_oleh' => $operatorId,
            'status_validasi' => 'pending',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
