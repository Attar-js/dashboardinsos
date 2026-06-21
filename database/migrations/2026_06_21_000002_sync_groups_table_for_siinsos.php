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

        Schema::table('groups', function (Blueprint $table) {
            if (!Schema::hasColumn('groups', 'nama_kelompok') && Schema::hasColumn('groups', 'title')) {
                $table->string('nama_kelompok')->nullable()->after('id');
            }
            if (!Schema::hasColumn('groups', 'judul_kegiatan')) {
                $table->text('judul_kegiatan')->nullable()->after('nama_kelompok');
            }
            if (!Schema::hasColumn('groups', 'lokasi_kkn')) {
                $table->string('lokasi_kkn')->nullable();
            }
            if (!Schema::hasColumn('groups', 'deskripsi_kegiatan')) {
                $table->text('deskripsi_kegiatan')->nullable();
            }
            if (!Schema::hasColumn('groups', 'nama_mitra')) {
                $table->string('nama_mitra')->nullable();
            }
            if (!Schema::hasColumn('groups', 'lokasi_mitra')) {
                $table->string('lokasi_mitra')->nullable();
            }
            if (!Schema::hasColumn('groups', 'dosen_id')) {
                $table->unsignedBigInteger('dosen_id')->nullable();
            }
            if (!Schema::hasColumn('groups', 'leader_id')) {
                $table->unsignedBigInteger('leader_id')->nullable()->after('dosen_id');
            }
            if (!Schema::hasColumn('groups', 'assigned_by')) {
                $table->unsignedBigInteger('assigned_by')->nullable();
            }
            if (!Schema::hasColumn('groups', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable();
            }
            if (!Schema::hasColumn('groups', 'supervisor_approved_at')) {
                $table->timestamp('supervisor_approved_at')->nullable()->after('assigned_at');
            }
            if (!Schema::hasColumn('groups', 'assignment_note')) {
                $table->text('assignment_note')->nullable();
            }
            if (!Schema::hasColumn('groups', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('groups', 'catatan')) {
                $table->text('catatan')->nullable();
            }
            if (!Schema::hasColumn('groups', 'proposal_review_status')) {
                $table->string('proposal_review_status', 20)->default('pending')->after('catatan');
            }
            if (!Schema::hasColumn('groups', 'proposal_review_note')) {
                $table->text('proposal_review_note')->nullable();
            }
            if (!Schema::hasColumn('groups', 'proposal_reviewed_at')) {
                $table->timestamp('proposal_reviewed_at')->nullable();
            }
            if (!Schema::hasColumn('groups', 'proposal_reviewed_by')) {
                $table->unsignedBigInteger('proposal_reviewed_by')->nullable();
            }
            if (!Schema::hasColumn('groups', 'progress_verifikasi')) {
                $table->integer('progress_verifikasi')->default(0);
            }
        });
    }

    public function down(): void
    {
        // Kolom shared SIINSOS — tidak di-drop otomatis.
    }
};
