<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Tes validasi backend untuk endpoint penerimaan proposal dari project-akhir.
 * Memastikan input sembarangan ditolak (422) sebelum tersimpan ke database.
 *
 * Jalankan:  php artisan test --filter=ProposalValidationTest
 */
class ProposalValidationTest extends TestCase
{
    private string $endpoint = '/api/proposal/store-from-external';

    private function dataValid(): array
    {
        return [
            'judul_kegiatan' => 'KKN Tematik Desa Sukamaju',
            'user_nim' => '2010001',
            'file_content' => base64_encode('isi-pdf-palsu'),
            'file_name' => 'proposal.pdf',
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

    public function test_menolak_judul_kegiatan_melebihi_255_karakter(): void
    {
        $data = $this->dataValid();
        $data['judul_kegiatan'] = str_repeat('a', 256);

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['judul_kegiatan']);
    }

    public function test_menolak_user_nim_melebihi_20_karakter(): void
    {
        $data = $this->dataValid();
        $data['user_nim'] = str_repeat('9', 21);

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user_nim']);
    }

    public function test_menolak_file_size_bukan_angka(): void
    {
        $data = $this->dataValid();
        $data['file_size'] = 'bukan-angka';

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file_size']);
    }
}
