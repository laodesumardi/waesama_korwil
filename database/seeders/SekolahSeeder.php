<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data korwil untuk mendapatkan id_korwil berdasarkan kode_wilayah
        $korwilMap = [];
        $korwils = DB::table('korwil')->select('id', 'kode_wilayah')->get();
        foreach ($korwils as $korwil) {
            $korwilMap[$korwil->kode_wilayah] = $korwil->id;
        }

        // Data sekolah berdasarkan kecamatan dengan id_korwil yang sesuai
        $sekolahData = [
            // NAMROLE (kecamatan) -> KW-001
            ['npsn' => '60101025', 'nama_sekolah' => 'SMP NEGERI 01 NAMROLE', 'alamat' => 'Jl. Pendidikan No. 1', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69728722', 'nama_sekolah' => 'SMP NEGERI SATAP 02 NAMROLE', 'alamat' => 'Jl. Poros Namrole', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '60103426', 'nama_sekolah' => 'SMP SATAP NEGERI 03 NAMROLE', 'alamat' => 'Jl. Desa Namrole', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '60103427', 'nama_sekolah' => 'SMP NEGERI 04 NAMROLE', 'alamat' => 'Jl. Raya Namrole', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69821206', 'nama_sekolah' => 'SMP NEGERI 05 NAMROLE', 'alamat' => 'Jl. Trans Namrole', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69862680', 'nama_sekolah' => 'SMP NEGERI SATAP 06 NAMROLE', 'alamat' => 'Jl. Pendidikan', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '60103425', 'nama_sekolah' => 'SMP NEGERI SATAP 07 NAMROLE', 'alamat' => 'Jl. Desa', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '60100992', 'nama_sekolah' => 'SMP SWASTA 08 NAMROLE', 'alamat' => 'Jl. Swasta No. 1', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '70047470', 'nama_sekolah' => 'SMP IT AL IKHSAN MADANI NAMROLE', 'alamat' => 'Jl. Pendidikan Islam', 'kelurahan' => 'Namrole', 'kecamatan' => 'NAMROLE', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],

            // WAESAMA (kecamatan) -> KW-001
            ['npsn' => '60100970', 'nama_sekolah' => 'SMP NEGERI 01 WAESAMA', 'alamat' => 'Jl. Waesama No. 1', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69728726', 'nama_sekolah' => 'SMP NEGERI SATAP 02 WAESAMA', 'alamat' => 'Jl. Poros Waesama', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69954586', 'nama_sekolah' => 'SMP NEGERI 03 WAESAMA', 'alamat' => 'Jl. Raya Waesama', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '60105599', 'nama_sekolah' => 'SMPN SATAP 04 WAESAMA', 'alamat' => 'Jl. Desa Waesama', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69949900', 'nama_sekolah' => 'SMP NEGERI 05 WAESAMA', 'alamat' => 'Jl. Trans Waesama', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '60103527', 'nama_sekolah' => 'SMP NEGERI 06 WAESAMA', 'alamat' => 'Jl. Pendidikan', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69872148', 'nama_sekolah' => 'SMP NEGERI 07 WAESAMA', 'alamat' => 'Jl. Baru', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '60101026', 'nama_sekolah' => 'SMP NEGERI 08 WAESAMA', 'alamat' => 'Jl. Waesama Indah', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],
            ['npsn' => '69900552', 'nama_sekolah' => 'SMP NEGERI 09 WAESAMA', 'alamat' => 'Jl. Terpadu', 'kelurahan' => 'Waesama', 'kecamatan' => 'WAESAMA', 'kode_korwil' => 'KW-001', 'status' => 'aktif'],

            // LEKSULA (kecamatan) -> KW-002
            ['npsn' => '60100989', 'nama_sekolah' => 'SMP NEGERI 01 LEKSULA', 'alamat' => 'Jl. Leksula No. 1', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '69728721', 'nama_sekolah' => 'SMP NEGERI 02 LEKSULA', 'alamat' => 'Jl. Poros Leksula', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '69973221', 'nama_sekolah' => 'SMP NEGERI 03 LEKSULA', 'alamat' => 'Jl. Raya Leksula', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '60103368', 'nama_sekolah' => 'SMP NEGERI SATAP 04 LEKSULA', 'alamat' => 'Jl. Desa Leksula', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '69970819', 'nama_sekolah' => 'SMP NEGERI 05 LEKSULA', 'alamat' => 'Jl. Trans Leksula', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '69882401', 'nama_sekolah' => 'SMP NEGERI 06 LEKSULA', 'alamat' => 'Jl. Pendidikan', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '69882400', 'nama_sekolah' => 'SMP NEGERI 07 LEKSULA', 'alamat' => 'Jl. Baru', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '60103542', 'nama_sekolah' => 'SMP NEGERI SATAP 08 LEKSULA', 'alamat' => 'Jl. Terpadu', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '60103371', 'nama_sekolah' => 'SMP NEGERI 09 LEKSULA', 'alamat' => 'Jl. Leksula Jaya', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '60100990', 'nama_sekolah' => 'SMP SWASTA 10 LEKSULA', 'alamat' => 'Jl. Swasta', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '60100995', 'nama_sekolah' => 'SMP SWASTA 11 LEKSULA', 'alamat' => 'Jl. Pendidikan Swasta', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '60101014', 'nama_sekolah' => 'SMP SWASTA 12 LEKSULA', 'alamat' => 'Jl. Mandiri', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],
            ['npsn' => '70003335', 'nama_sekolah' => 'SMP NEGERI 13 LEKSULA', 'alamat' => 'Jl. Leksula Baru', 'kelurahan' => 'Leksula', 'kecamatan' => 'LEKSULA', 'kode_korwil' => 'KW-002', 'status' => 'aktif'],

            // AMBALAU (kecamatan) -> KW-003
            ['npsn' => '60100983', 'nama_sekolah' => 'SMP NEGERI 01 AMBALAU', 'alamat' => 'Jl. Ambalau No. 1', 'kelurahan' => 'Ambalau', 'kecamatan' => 'AMBALAU', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '69988360', 'nama_sekolah' => 'SMP NEGERI SATAP 02 AMBALAU', 'alamat' => 'Jl. Poros Ambalau', 'kelurahan' => 'Ambalau', 'kecamatan' => 'AMBALAU', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '60101021', 'nama_sekolah' => 'SMP NEGERI 03 AMBALAU', 'alamat' => 'Jl. Raya Ambalau', 'kelurahan' => 'Ambalau', 'kecamatan' => 'AMBALAU', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '69821205', 'nama_sekolah' => 'SMP NEGERI SATAP 04 AMBALAU', 'alamat' => 'Jl. Desa Ambalau', 'kelurahan' => 'Ambalau', 'kecamatan' => 'AMBALAU', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '60100987', 'nama_sekolah' => 'SMP NEGERI 05 AMBALAU', 'alamat' => 'Jl. Trans Ambalau', 'kelurahan' => 'Ambalau', 'kecamatan' => 'AMBALAU', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],

            // KEPALA MADAN (kecamatan) -> KW-003
            ['npsn' => '60100969', 'nama_sekolah' => 'SMP NEGERI 01 KEPALA MADAN', 'alamat' => 'Jl. Kepala Madan No. 1', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '60103524', 'nama_sekolah' => 'SMP NEGERI SATAP 02 KEPALA MADAN', 'alamat' => 'Jl. Poros Kepala Madan', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '69983684', 'nama_sekolah' => 'SMP NEGERI 03 KEPALA MADAN', 'alamat' => 'Jl. Raya Kepala Madan', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '60100994', 'nama_sekolah' => 'SMP NEGERI 04 KEPALA MADAN', 'alamat' => 'Jl. Desa Kepala Madan', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '60103865', 'nama_sekolah' => 'SMP NEGERI 05 KEPALA MADAN', 'alamat' => 'Jl. Trans Kepala Madan', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '60100982', 'nama_sekolah' => 'SMP NEGERI 06 KEPALA MADAN', 'alamat' => 'Jl. Pendidikan', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '69903905', 'nama_sekolah' => 'SMP NEGERI 07 KEPALA MADAN', 'alamat' => 'Jl. Baru', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],
            ['npsn' => '60103424', 'nama_sekolah' => 'SMP NEGERI SATU ATAP 08 KEPALA MADAN', 'alamat' => 'Jl. Terpadu', 'kelurahan' => 'Kepala Madan', 'kecamatan' => 'KEPALA MADAN', 'kode_korwil' => 'KW-003', 'status' => 'aktif'],

            // FENAFAFAN (kecamatan) -> KW-004
            ['npsn' => '69882402', 'nama_sekolah' => 'SMP NEGERI 01 FENAFAFAN', 'alamat' => 'Jl. Fenafafan No. 1', 'kelurahan' => 'Fenafafan', 'kecamatan' => 'FENAFAFAN', 'kode_korwil' => 'KW-004', 'status' => 'aktif'],
            ['npsn' => '60103539', 'nama_sekolah' => 'SMP NEGERI 02 FENAFAFAN', 'alamat' => 'Jl. Poros Fenafafan', 'kelurahan' => 'Fenafafan', 'kecamatan' => 'FENAFAFAN', 'kode_korwil' => 'KW-004', 'status' => 'aktif'],
            ['npsn' => '69954589', 'nama_sekolah' => 'SMP NEGERI 03 FENAFAFAN', 'alamat' => 'Jl. Raya Fenafafan', 'kelurahan' => 'Fenafafan', 'kecamatan' => 'FENAFAFAN', 'kode_korwil' => 'KW-004', 'status' => 'aktif'],
            ['npsn' => '69949877', 'nama_sekolah' => 'SMP NEGERI SATAP 04 FENAFAFAN', 'alamat' => 'Jl. Desa Fenafafan', 'kelurahan' => 'Fenafafan', 'kecamatan' => 'FENAFAFAN', 'kode_korwil' => 'KW-004', 'status' => 'aktif'],
        ];

        $now = Carbon::now();

        foreach ($sekolahData as $data) {
            $idKorwil = $korwilMap[$data['kode_korwil']] ?? null;

            if ($idKorwil) {
                DB::table('sekolah')->insert([
                    'npsn' => $data['npsn'],
                    'nama_sekolah' => $data['nama_sekolah'],
                    'alamat' => $data['alamat'],
                    'kelurahan' => $data['kelurahan'],
                    'kecamatan' => $data['kecamatan'],
                    'id_korwil' => $idKorwil,
                    'status' => $data['status'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                $this->command->warn("⚠️ Korwil dengan kode {$data['kode_korwil']} tidak ditemukan untuk sekolah {$data['nama_sekolah']}");
            }
        }

        $this->command->info('✅ Data sekolah berhasil di-seed!');
        $this->command->info('Total: ' . count($sekolahData) . ' sekolah');
    }
}
