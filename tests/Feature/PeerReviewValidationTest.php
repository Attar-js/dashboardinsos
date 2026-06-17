<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Tes validasi backend untuk endpoint peer review dari project-akhir.
 * Memastikan input sembarangan ditolak (422) sebelum tersimpan ke database.
 *
 * Jalankan:  php artisan test --filter=PeerReviewValidationTest
 */
class PeerReviewValidationTest extends TestCase
{
    private string $endpoint = '/api/peer-review/store-from-external';

    private function dataValid(): array
    {
        return [
            'judul_kegiatan' => 'KKN Tematik Desa Sukamaju',
            'user_nim' => '2010001',
            'file_content' => base64_encode('isi-pdf-palsu'),
            'file_name' => 'peer-review.pdf',
            'file_mime_type' => 'application/pdf',
            'file_size' => 204800,
        ];
    }

    public function test_menolak_jika_semua_input_kosong(): void
    {
        $this->postJson($this->endpoint, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'judul_kegiatan', 'user_nim', 'file_content',
                'file_name', 'file_mime_type', 'file_size',
            ]);
    }

    public function test_menolak_file_size_bukan_angka(): void
    {
        $data = $this->dataValid();
        $data['file_size'] = 'bukan-angka';

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file_size']);
    }

    public function test_status_menolak_nilai_selain_yang_diizinkan(): void
    {
        $this->putJson('/api/peer-review/1/status', ['status' => 'ngawur'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
