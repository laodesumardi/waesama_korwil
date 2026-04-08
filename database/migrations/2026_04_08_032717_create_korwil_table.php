<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('korwil', function (Blueprint $table) {
            $table->id(); // BIGINT PK

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('kode_wilayah', 20)->unique();
            $table->string('nama_korwil', 100);
            $table->text('wilayah_kerja');
            $table->string('no_sk', 100);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('korwil');
    }
};
