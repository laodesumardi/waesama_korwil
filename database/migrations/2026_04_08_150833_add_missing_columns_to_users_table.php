<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom dengan pengecekan
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin_dinas', 'operator_sekolah', 'korwil'])->default('operator_sekolah')->after('foto');
            }

            if (!Schema::hasColumn('users', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('created_by');
            }

            if (!Schema::hasColumn('users', 'last_login')) {
                $table->timestamp('last_login')->nullable()->after('is_active');
            }
        });

        // Tambah foreign key (coba, jika gagal skip)
        if (Schema::hasColumn('users', 'created_by')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key sudah ada, abaikan
            }
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropForeign(['created_by']);
            } catch (\Exception $e) {
            }

            $columns = ['foto', 'role', 'created_by', 'is_active', 'last_login'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
