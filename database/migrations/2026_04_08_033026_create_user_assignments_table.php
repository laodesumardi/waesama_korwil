<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('target_type', ['sekolah', 'korwil']);

            $table->unsignedBigInteger('target_id');

            $table->timestamps();

            // Unique constraint
            $table->unique(['user_id', 'target_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_assignments');
    }
};
