<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_ajaran', function (Blueprint $table) {
            $table->id();

            $table->string('tahun_ajaran', 20); // contoh: 2024/2025

            $table->enum('semester', ['1', '2']);

            $table->boolean('is_active')->default(false);

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_ajaran');
    }
};
