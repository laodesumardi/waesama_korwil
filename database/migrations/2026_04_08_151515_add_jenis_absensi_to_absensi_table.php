<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambah
            if (!Schema::hasColumn('absensi', 'jenis_absensi')) {
                $table->enum('jenis_absensi', ['siswa', 'guru'])->default('siswa')->after('id_periode');
            }

            // Cek dan tambah kolom detail_absensi jika belum ada
            if (!Schema::hasColumn('absensi', 'detail_absensi')) {
                $table->json('detail_absensi')->nullable()->after('keterangan');
            }
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            if (Schema::hasColumn('absensi', 'jenis_absensi')) {
                $table->dropColumn('jenis_absensi');
            }
            if (Schema::hasColumn('absensi', 'detail_absensi')) {
                $table->dropColumn('detail_absensi');
            }
        });
    }
};
