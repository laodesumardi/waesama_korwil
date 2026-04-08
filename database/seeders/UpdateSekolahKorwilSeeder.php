<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSekolahKorwilSeeder extends Seeder
{
    public function run(): void
    {
        // Mapping kecamatan ke korwil
        $mapping = [
            'NAMROLE' => 1,      // KW-001
            'WAESAMA' => 1,      // KW-001
            'LEKSULA' => 2,      // KW-002
            'AMBALAU' => 3,      // KW-003
            'KEPALA MADAN' => 3, // KW-003
            'FENAFAFAN' => 4,    // KW-004
        ];

        foreach ($mapping as $kecamatan => $korwilId) {
            DB::table('sekolah')
                ->where('kecamatan', $kecamatan)
                ->update(['id_korwil' => $korwilId]);
        }

        $this->command->info('id_korwil pada tabel sekolah berhasil diupdate!');

        // Tampilkan statistik
        $stats = DB::table('sekolah')
            ->select('kecamatan', DB::raw('count(*) as total'))
            ->groupBy('kecamatan')
            ->get();

        foreach ($stats as $stat) {
            $this->command->info("- {$stat->kecamatan}: {$stat->total} sekolah");
        }
    }
}
