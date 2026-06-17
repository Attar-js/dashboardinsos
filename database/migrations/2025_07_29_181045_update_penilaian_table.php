<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('penilaian')) {
            return;
        }

        Schema::table('penilaian', function (Blueprint $table) {
            foreach (['nilai_kehadiran', 'nilai_tugas', 'nilai_praktikum', 'nilai_ujian'] as $col) {
                if (Schema::hasColumn('penilaian', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('penilaian', function (Blueprint $table) {
            if (! Schema::hasColumn('penilaian', 'proposal_kegiatan')) {
                $table->decimal('proposal_kegiatan', 5, 2)->nullable();
            }
            if (! Schema::hasColumn('penilaian', 'peer_review')) {
                $table->decimal('peer_review', 5, 2)->nullable();
            }
            if (! Schema::hasColumn('penilaian', 'laporan_akhir')) {
                $table->decimal('laporan_akhir', 5, 2)->nullable();
            }
            if (! Schema::hasColumn('penilaian', 'presentasi_akhir')) {
                $table->decimal('presentasi_akhir', 5, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('penilaian')) {
            return;
        }

        Schema::table('penilaian', function (Blueprint $table) {
            foreach (['proposal_kegiatan', 'peer_review', 'laporan_akhir', 'presentasi_akhir'] as $col) {
                if (Schema::hasColumn('penilaian', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('penilaian', function (Blueprint $table) {
            if (! Schema::hasColumn('penilaian', 'nilai_kehadiran')) {
                $table->decimal('nilai_kehadiran', 5, 2)->nullable();
            }
            if (! Schema::hasColumn('penilaian', 'nilai_tugas')) {
                $table->decimal('nilai_tugas', 5, 2)->nullable();
            }
            if (! Schema::hasColumn('penilaian', 'nilai_praktikum')) {
                $table->decimal('nilai_praktikum', 5, 2)->nullable();
            }
            if (! Schema::hasColumn('penilaian', 'nilai_ujian')) {
                $table->decimal('nilai_ujian', 5, 2)->nullable();
            }
        });
    }
};
