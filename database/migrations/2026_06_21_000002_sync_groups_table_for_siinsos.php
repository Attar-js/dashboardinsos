<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyelaraskan tabel groups dengan skema SIINSOS (landing page).
 * Aman dijalankan berulang: cek hasColumn / hasTable.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('groups')) {
            return;
        }

        $columns = [
            'nama_kelompok' => fn (Blueprint $table) => $table->string('nama_kelompok')->nullable(),
            'judul_kegiatan' => fn (Blueprint $table) => $table->text('judul_kegiatan')->nullable(),
            'lokasi_kkn' => fn (Blueprint $table) => $table->string('lokasi_kkn')->nullable(),
            'deskripsi_kegiatan' => fn (Blueprint $table) => $table->text('deskripsi_kegiatan')->nullable(),
            'nama_mitra' => fn (Blueprint $table) => $table->string('nama_mitra')->nullable(),
            'lokasi_mitra' => fn (Blueprint $table) => $table->string('lokasi_mitra')->nullable(),
            'dosen_id' => fn (Blueprint $table) => $table->unsignedBigInteger('dosen_id')->nullable(),
            'leader_id' => fn (Blueprint $table) => $table->unsignedBigInteger('leader_id')->nullable(),
            'assigned_by' => fn (Blueprint $table) => $table->unsignedBigInteger('assigned_by')->nullable(),
            'assigned_at' => fn (Blueprint $table) => $table->timestamp('assigned_at')->nullable(),
            'supervisor_approved_at' => fn (Blueprint $table) => $table->timestamp('supervisor_approved_at')->nullable(),
            'assignment_note' => fn (Blueprint $table) => $table->text('assignment_note')->nullable(),
            'status' => fn (Blueprint $table) => $table->string('status')->default('pending'),
            'catatan' => fn (Blueprint $table) => $table->text('catatan')->nullable(),
            'proposal_review_status' => fn (Blueprint $table) => $table->string('proposal_review_status', 20)->default('pending'),
            'proposal_review_note' => fn (Blueprint $table) => $table->text('proposal_review_note')->nullable(),
            'proposal_reviewed_at' => fn (Blueprint $table) => $table->timestamp('proposal_reviewed_at')->nullable(),
            'proposal_reviewed_by' => fn (Blueprint $table) => $table->unsignedBigInteger('proposal_reviewed_by')->nullable(),
            'progress_verifikasi' => fn (Blueprint $table) => $table->integer('progress_verifikasi')->default(0),
        ];

        foreach ($columns as $name => $definition) {
            if (!Schema::hasColumn('groups', $name)) {
                Schema::table('groups', $definition);
            }
        }
    }

    public function down(): void
    {
        // Kolom shared SIINSOS — tidak di-drop otomatis.
    }
};
