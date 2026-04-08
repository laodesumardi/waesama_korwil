<?php
// database/migrations/2026_04_09_000001_fix_users_table_foreign_key.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya cek dan hapus foreign key jika ada
            $foreignKeys = $this->getForeignKeys('users');

            if (in_array('users_created_by_foreign', $foreignKeys)) {
                $table->dropForeign('users_created_by_foreign');
            }

            // Cek dan tambah kolom yang belum ada
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin_dinas', 'operator_sekolah', 'korwil'])->after('foto');
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

            // Tambah kembali foreign key
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['foto', 'role', 'created_by', 'is_active', 'last_login']);
        });
    }

    // Helper function untuk mendapatkan foreign keys
    private function getForeignKeys($table)
    {
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();

        $results = $conn->select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$dbName, $table]);

        return array_column($results, 'CONSTRAINT_NAME');
    }
};
