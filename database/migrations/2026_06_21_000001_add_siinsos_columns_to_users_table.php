<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah kolom users yang dipakai SIINSOS (landing page + dashboard).
 * Aman dijalankan berulang: cek hasColumn.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim', 30)->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip', 30)->nullable()->after('nim');
            }
            if (!Schema::hasColumn('users', 'program_studi')) {
                $table->string('program_studi', 100)->nullable()->after('nip');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 30)->nullable()->after('user_type');
            }
        });
    }

    public function down(): void
    {
        // Kolom shared SIINSOS — tidak di-drop otomatis.
    }
};
