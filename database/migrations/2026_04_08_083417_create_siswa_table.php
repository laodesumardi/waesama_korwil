<?php
// database/migrations/xxxx_xx_xx_create_siswa_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique(); // Nomor Induk Siswa Nasional
            $table->string('nis', 20)->unique(); // Nomor Induk Sekolah
            $table->string('nama_siswa', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->string('kelas', 20); // Contoh: 7A, 8B, 9C
            $table->string('alamat')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->foreignId('id_sekolah')->constrained('sekolah')->onDelete('cascade');
            $table->enum('status', ['aktif', 'pindah', 'keluar', 'lulus'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswa');
    }
};
