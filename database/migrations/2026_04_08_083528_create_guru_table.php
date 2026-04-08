<?php
// database/migrations/xxxx_xx_xx_create_guru_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20)->unique(); // Nomor Induk Pegawai
            $table->string('nuptk', 20)->unique()->nullable(); // Nomor Unik Pendidik dan Tenaga Kependidikan
            $table->string('nama_guru', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->string('pendidikan_terakhir', 50); // S1, S2, S3
            $table->string('bidang_studi', 100); // Matematika, Bahasa Indonesia, dll
            $table->string('alamat')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('email')->unique()->nullable();
            $table->foreignId('id_sekolah')->constrained('sekolah')->onDelete('cascade');
            $table->enum('status', ['aktif', 'nonaktif', 'pindah', 'pensiun'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guru');
    }
};
