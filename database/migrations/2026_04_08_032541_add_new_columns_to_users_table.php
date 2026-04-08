<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('foto')->nullable()->after('password');

            $table->enum('role', [
                'admin_dinas',
                'operator_sekolah',
                'korwil'
            ])->default('operator_sekolah')->after('foto');

            $table->foreignId('created_by')
                ->nullable()
                ->after('role')
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true)->after('created_by');

            $table->timestamp('last_login')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'foto',
                'role',
                'created_by',
                'is_active',
                'last_login'
            ]);
        });
    }
};
