<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Membuat tabel bisnis SIINSOS yang biasanya berasal dari migrasi landing page.
 * Dipakai saat setup device hanya menjalankan migrate dashboard.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->createPenilaianTable();
        $this->createPeerReviewTable();
        $this->createFormKesediaanTable();
        $this->createKknTables();
        $this->createDocumentTables();
        $this->createSupervisorRequestsTable();
        $this->createGroupCpmkRubricsTable();
        $this->createGroupDocumentReviewsTable();
        $this->createNilaiCpmkTable();
        $this->createUserNotificationsTable();
    }

    private function createPenilaianTable(): void
    {
        if (Schema::hasTable('penilaian')) {
            return;
        }

        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_nim', 30);
            $table->unsignedBigInteger('dosen_id');
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->decimal('proposal_kegiatan', 5, 2)->nullable();
            $table->decimal('asistensi', 5, 2)->nullable();
            $table->decimal('peer_review', 5, 2)->nullable();
            $table->decimal('laporan_akhir', 5, 2)->nullable();
            $table->decimal('presentasi_akhir', 5, 2)->nullable();
            $table->decimal('pembimbing_lapangan', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_penilaian')->nullable();
            $table->timestamps();

            $table->foreign('dosen_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['mahasiswa_nim', 'dosen_id'], 'penilaian_mahasiswa_nim_dosen_id_unique');
        });
    }

    private function createPeerReviewTable(): void
    {
        if (Schema::hasTable('peer_review')) {
            return;
        }

        Schema::create('peer_review', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->unsignedBigInteger('reviewee_id')->nullable();
            $table->string('judul_kegiatan')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('user_nim', 30)->nullable();
            $table->string('status', 30)->default('pending');
            $table->text('catatan')->nullable();
            $table->decimal('kontribusi_kegiatan', 5, 2)->nullable();
            $table->decimal('tanggung_jawab', 5, 2)->nullable();
            $table->decimal('kerjasama_tim', 5, 2)->nullable();
            $table->decimal('inisiatif_motivasi', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    private function createFormKesediaanTable(): void
    {
        if (Schema::hasTable('form_kesediaan')) {
            return;
        }

        Schema::create('form_kesediaan', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('user_nim', 30)->nullable();
            $table->string('status', 30)->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    private function createKknTables(): void
    {
        if (!Schema::hasTable('kkn_pendaftar')) {
            Schema::create('kkn_pendaftar', function (Blueprint $table) {
                $table->id();
                $table->string('judul_kegiatan');
                $table->string('mitra');
                $table->string('lokasi_mitra');
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->string('status', 30)->default('pending');
                $table->string('status_verifikasi', 30)->nullable();
                $table->string('user_nim', 30)->nullable();
                $table->text('catatan_verifikasi')->nullable();
                $table->timestamp('tanggal_verifikasi')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kkn_anggota')) {
            Schema::create('kkn_anggota', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('kkn_pendaftar_id');
                $table->string('nama');
                $table->string('nim');
                $table->string('program_studi');
                $table->string('peran', 20)->default('Anggota');
                $table->timestamps();

                $table->foreign('kkn_pendaftar_id')->references('id')->on('kkn_pendaftar')->onDelete('cascade');
            });
        }
    }

    private function createDocumentTables(): void
    {
        if (!Schema::hasTable('proposal')) {
            Schema::create('proposal', function (Blueprint $table) {
                $table->id();
                $table->string('judul_kegiatan');
                $table->string('user_nim', 30);
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->longText('file_content')->nullable();
                $table->string('file_mime_type', 100)->nullable();
                $table->string('status', 30)->default('pending');
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->index(['user_nim', 'judul_kegiatan']);
            });
        }

        if (!Schema::hasTable('laporan_akhir')) {
            Schema::create('laporan_akhir', function (Blueprint $table) {
                $table->id();
                $table->string('judul_kegiatan');
                $table->string('user_nim', 30);
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->longText('file_content')->nullable();
                $table->string('file_mime_type', 100)->nullable();
                $table->string('status', 30)->default('pending');
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->index(['user_nim', 'judul_kegiatan']);
            });
        }

        if (!Schema::hasTable('luaran')) {
            Schema::create('luaran', function (Blueprint $table) {
                $table->id();
                $table->string('judul_kegiatan');
                $table->string('user_nim', 30)->nullable();
                $table->string('video_aftermovie')->default('');
                $table->string('artikel_link')->default('');
                $table->string('artikel_file_path')->nullable();
                $table->string('artikel_file_name')->nullable();
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->string('status', 30)->default('pending');
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->index(['user_nim', 'judul_kegiatan']);
            });
        }
    }

    private function createSupervisorRequestsTable(): void
    {
        if (Schema::hasTable('supervisor_requests')) {
            return;
        }

        Schema::create('supervisor_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->unsignedBigInteger('requested_by');
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->index(['supervisor_id', 'status']);
        });
    }

    private function createGroupCpmkRubricsTable(): void
    {
        if (Schema::hasTable('group_cpmk_rubrics')) {
            return;
        }

        Schema::create('group_cpmk_rubrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->unique();
            $table->text('deskripsi_p5')->nullable();
            $table->text('deskripsi_c3')->nullable();
            $table->text('deskripsi_a2')->nullable();
            $table->decimal('skor_p5', 5, 2)->nullable();
            $table->decimal('skor_c3', 5, 2)->nullable();
            $table->decimal('skor_a2', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('filled_by')->nullable();
            $table->unsignedBigInteger('skor_filled_by')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('filled_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    private function createGroupDocumentReviewsTable(): void
    {
        if (Schema::hasTable('group_document_reviews')) {
            return;
        }

        Schema::create('group_document_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->unique();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('laporan_status', 30)->nullable();
            $table->string('artikel_status', 30)->nullable();
            $table->string('video_status', 30)->nullable();
            $table->text('note')->nullable();
            $table->text('laporan_note')->nullable();
            $table->text('artikel_note')->nullable();
            $table->text('video_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    private function createNilaiCpmkTable(): void
    {
        if (Schema::hasTable('nilai_cpmk')) {
            return;
        }

        Schema::create('nilai_cpmk', function (Blueprint $table) {
            $table->id();
            $table->string('nim_mahasiswa', 20);
            $table->string('nama_mahasiswa');
            $table->string('judul_kegiatan');
            $table->string('file_name');
            $table->longText('file_content')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('uploaded_by');
            $table->string('status', 30)->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->index(['nim_mahasiswa']);
            $table->index(['status']);
        });
    }

    private function createUserNotificationsTable(): void
    {
        if (Schema::hasTable('user_notifications')) {
            return;
        }

        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->default('Notifikasi Terbaru');
            $table->text('message');
            $table->string('icon')->default('bell');
            $table->string('link')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        // Tabel bisnis SIINSOS — tidak di-drop otomatis.
    }
};
