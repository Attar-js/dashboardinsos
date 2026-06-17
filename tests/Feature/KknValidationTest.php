<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Tes validasi backend untuk endpoint pendaftaran KKN.
 *
 * Semua tes di sini mengirim data yang SENGAJA salah, lalu memastikan
 * backend menolaknya dengan status 422 (Unprocessable Content).
 * Karena ditolak di tahap validasi, tes ini TIDAK menyimpan apa pun
 * ke database, jadi aman dijalankan terhadap data asli.
 *
 * Cara menjalankan:  php artisan test --filter=KknValidationTest
 */
class KknValidationTest extends TestCase
{
    private string $endpoint = '/api/kkn/store-from-external';

    /** Data valid sebagai titik awal; tiap tes merusak satu bagian saja. */
    private function dataValid(): array
    {
        return [
            'judul_kegiatan' => 'KKN Tematik Desa Sukamaju',
            'mitra' => 'Pemerintah Desa Sukamaju',
            'lokasi_mitra' => 'Desa Sukamaju, Kab. Bandung',
            'nama' => ['Budi Santoso'],
            'nim' => ['2010001'],
            'prodi' => ['Teknik Informatika'],
            'peran' => ['Ketua'],
            'user_nim' => '2010001',
            'file' => UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf'),
        ];
    }

    public function test_menolak_jika_semua_input_kosong(): void
    {
        $response = $this->postJson($this->endpoint, []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'judul_kegiatan', 'mitra', 'lokasi_mitra',
                'nama', 'nim', 'prodi', 'peran', 'file', 'user_nim',
            ]);
    }

    public function test_menolak_judul_kegiatan_kosong(): void
    {
        $data = $this->dataValid();
        unset($data['judul_kegiatan']);

        $this->post($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['judul_kegiatan']);
    }

    public function test_menolak_file_bukan_pdf(): void
    {
        $data = $this->dataValid();
        $data['file'] = UploadedFile::fake()->create('dokumen.txt', 100, 'text/plain');

        $this->post($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_menolak_file_melebihi_10mb(): void
    {
        $data = $this->dataValid();
        // 10240 KB = batas maksimal; 11000 KB melebihi batas.
        $data['file'] = UploadedFile::fake()->create('besar.pdf', 11000, 'application/pdf');

        $this->post($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_menolak_peran_selain_ketua_atau_anggota(): void
    {
        $data = $this->dataValid();
        $data['peran'] = ['Bos'];

        $this->post($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['peran.0']);
    }

    public function test_menolak_nama_bukan_array(): void
    {
        $data = $this->dataValid();
        $data['nama'] = 'Budi Santoso';

        $this->post($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nama']);
    }
}
