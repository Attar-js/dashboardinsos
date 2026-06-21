<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Perbaikan kolom groups SIINSOS — satu ALTER per kolom (tanpa AFTER)
 * agar aman di semua versi MySQL/MariaDB dan skema groups lama dashboard.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('groups')) {
            return;
        }

        $this->addColumnIfMissing('nama_kelompok', function (Blueprint $table) {
            $table->string('nama_kelompok')->nullable();
        });
        $this->addColumnIfMissing('judul_kegiatan', function (Blueprint $table) {
            $table->text('judul_kegiatan')->nullable();
        });
        $this->addColumnIfMissing('lokasi_kkn', function (Blueprint $table) {
            $table->string('lokasi_kkn')->nullable();
        });
        $this->addColumnIfMissing('deskripsi_kegiatan', function (Blueprint $table) {
            $table->text('deskripsi_kegiatan')->nullable();
        });
        $this->addColumnIfMissing('nama_mitra', function (Blueprint $table) {
            $table->string('nama_mitra')->nullable();
        });
        $this->addColumnIfMissing('lokasi_mitra', function (Blueprint $table) {
            $table->string('lokasi_mitra')->nullable();
        });
        $this->addColumnIfMissing('dosen_id', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_id')->nullable();
        });
        $this->addColumnIfMissing('leader_id', function (Blueprint $table) {
            $table->unsignedBigInteger('leader_id')->nullable();
        });
        $this->addColumnIfMissing('assigned_by', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_by')->nullable();
        });
        $this->addColumnIfMissing('assigned_at', function (Blueprint $table) {
            $table->timestamp('assigned_at')->nullable();
        });
        $this->addColumnIfMissing('supervisor_approved_at', function (Blueprint $table) {
            $table->timestamp('supervisor_approved_at')->nullable();
        });
        $this->addColumnIfMissing('assignment_note', function (Blueprint $table) {
            $table->text('assignment_note')->nullable();
        });
        $this->addColumnIfMissing('status', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
        $this->addColumnIfMissing('catatan', function (Blueprint $table) {
            $table->text('catatan')->nullable();
        });
        $this->addColumnIfMissing('proposal_review_status', function (Blueprint $table) {
            $table->string('proposal_review_status', 20)->default('pending');
        });
        $this->addColumnIfMissing('proposal_review_note', function (Blueprint $table) {
            $table->text('proposal_review_note')->nullable();
        });
        $this->addColumnIfMissing('proposal_reviewed_at', function (Blueprint $table) {
            $table->timestamp('proposal_reviewed_at')->nullable();
        });
        $this->addColumnIfMissing('proposal_reviewed_by', function (Blueprint $table) {
            $table->unsignedBigInteger('proposal_reviewed_by')->nullable();
        });
        $this->addColumnIfMissing('progress_verifikasi', function (Blueprint $table) {
            $table->integer('progress_verifikasi')->default(0);
        });
    }

    private function addColumnIfMissing(string $column, callable $definition): void
    {
        if (!Schema::hasColumn('groups', $column)) {
            Schema::table('groups', $definition);
        }
    }

    public function down(): void
    {
        // Kolom shared SIINSOS — tidak di-drop otomatis.
    }
};
