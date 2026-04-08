<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_sekolah')
                ->constrained('sekolah')
                ->cascadeOnDelete();

            $table->foreignId('id_periode')
                ->constrained('periode_ajaran')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->integer('jumlah_hadir')->default(0);
            $table->integer('jumlah_sakit')->default(0);
            $table->integer('jumlah_izin')->default(0);
            $table->integer('jumlah_alpha')->default(0);

            // total siswa (computed manual / generated nanti)
            $table->integer('total_siswa');

            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();

            $table->foreignId('diinput_oleh')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('status_validasi', [
                'pending',
                'disetujui',
                'ditolak'
            ])->default('pending');

            $table->foreignId('divalidasi_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });

        // CHECK constraint (MySQL 8+)
        DB::statement("
            ALTER TABLE absensi
            ADD CONSTRAINT check_total_siswa
            CHECK (
                jumlah_hadir + jumlah_sakit + jumlah_izin + jumlah_alpha = total_siswa
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
