<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class KorwilSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user untuk korwil terlebih dahulu jika belum ada
        $korwilUsers = [
            [
                'name' => 'Ahmad Yani',
                'email' => 'ahmad.yani@korwil.com',
                'password' => bcrypt('password123'),
                'role' => 'korwil',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@korwil.com',
                'password' => bcrypt('password123'),
                'role' => 'korwil',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@korwil.com',
                'password' => bcrypt('password123'),
                'role' => 'korwil',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Nurul Hidayah',
                'email' => 'nurul.hidayah@korwil.com',
                'password' => bcrypt('password123'),
                'role' => 'korwil',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        $userIds = [];
        foreach ($korwilUsers as $userData) {
            // Cek apakah user sudah ada
            $user = User::where('email', $userData['email'])->first();
            if (!$user) {
                $user = User::create($userData);
            }
            $userIds[] = $user->id;
        }

        // Data korwil dengan user_id
        $korwilData = [
            [
                'user_id' => $userIds[0] ?? null,
                'kode_wilayah' => 'KW-001',
                'nama_korwil' => 'Drs. Ahmad Yani, M.Pd',
                'wilayah_kerja' => 'Kecamatan NAMROLE dan WAESAMA',
                'no_sk' => '800/01/SK/DISDIK/2024',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $userIds[1] ?? null,
                'kode_wilayah' => 'KW-002',
                'nama_korwil' => 'Dra. Siti Rahayu, M.Si',
                'wilayah_kerja' => 'Kecamatan LEKSULA',
                'no_sk' => '800/02/SK/DISDIK/2024',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $userIds[2] ?? null,
                'kode_wilayah' => 'KW-003',
                'nama_korwil' => 'Drs. Budi Santoso, M.Pd',
                'wilayah_kerja' => 'Kecamatan AMBALAU dan KEPALA MADAN',
                'no_sk' => '800/03/SK/DISDIK/2024',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => $userIds[3] ?? null,
                'kode_wilayah' => 'KW-004',
                'nama_korwil' => 'Hj. Nurul Hidayah, S.Pd, M.Pd',
                'wilayah_kerja' => 'Kecamatan FENAFAFAN',
                'no_sk' => '800/04/SK/DISDIK/2024',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($korwilData as $data) {
            // Cek apakah korwil sudah ada
            $exists = DB::table('korwil')->where('kode_wilayah', $data['kode_wilayah'])->exists();
            if (!$exists) {
                DB::table('korwil')->insert($data);
            }
        }

        $this->command->info('✅ Data korwil berhasil di-seed!');
    }
}
