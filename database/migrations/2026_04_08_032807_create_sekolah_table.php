<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id(); // BIGINT PK

            $table->string('npsn', 20)->unique();
            $table->string('nama_sekolah', 200);
            $table->text('alamat');
            $table->string('kelurahan', 100);
            $table->string('kecamatan', 100);

            $table->foreignId('id_korwil')
                ->constrained('korwil')
                ->cascadeOnDelete();

            $table->enum('status', ['aktif', 'nonaktif'])
                ->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
