<?php
// database/migrations/xxxx_xx_xx_add_columns_to_absensi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->enum('jenis_absensi', ['siswa', 'guru'])->default('siswa')->after('id_periode');
            $table->json('detail_absensi')->nullable()->after('keterangan'); // Menyimpan detail per siswa/guru
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn(['jenis_absensi', 'detail_absensi']);
        });
    }
};
