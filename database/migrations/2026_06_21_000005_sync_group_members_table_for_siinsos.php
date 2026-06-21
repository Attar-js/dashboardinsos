<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan tabel group_members dengan skema SIINSOS (landing page).
 * Migrasi dashboard lama hanya punya group_id + name.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('group_members')) {
            Schema::create('group_members', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('group_id');
                $table->unsignedBigInteger('mahasiswa_id')->nullable();
                $table->string('role')->default('member');
                $table->string('status')->default('active');
                $table->timestamp('dropped_at')->nullable();
                $table->text('drop_reason')->nullable();
                $table->timestamps();

                $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
                $table->foreign('mahasiswa_id')->references('id')->on('users')->onDelete('cascade');
            });

            return;
        }

        $columns = [
            'mahasiswa_id' => fn (Blueprint $table) => $table->unsignedBigInteger('mahasiswa_id')->nullable(),
            'role' => fn (Blueprint $table) => $table->string('role')->default('member'),
            'status' => fn (Blueprint $table) => $table->string('status')->default('active'),
            'dropped_at' => fn (Blueprint $table) => $table->timestamp('dropped_at')->nullable(),
            'drop_reason' => fn (Blueprint $table) => $table->text('drop_reason')->nullable(),
        ];

        foreach ($columns as $name => $definition) {
            if (!Schema::hasColumn('group_members', $name)) {
                Schema::table('group_members', $definition);
            }
        }
    }

    public function down(): void
    {
        // Kolom shared SIINSOS — tidak di-drop otomatis.
    }
};
