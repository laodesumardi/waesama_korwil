<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('sekolah', 'nama_kepala_sekolah')) {
                $table->string('nama_kepala_sekolah')->nullable()->after('status');
            }
            if (!Schema::hasColumn('sekolah', 'no_telp_kepala_sekolah')) {
                $table->string('no_telp_kepala_sekolah', 20)->nullable()->after('nama_kepala_sekolah');
            }
        });
    }

    public function down()
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn(['nama_kepala_sekolah', 'no_telp_kepala_sekolah']);
        });
    }
};
