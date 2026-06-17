<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Tes validasi backend untuk endpoint luaran dari project-akhir.
 * Memastikan input sembarangan ditolak (422) sebelum tersimpan ke database.
 *
 * Jalankan:  php artisan test --filter=LuaranValidationTest
 */
class LuaranValidationTest extends TestCase
{
    private string $endpoint = '/api/luaran/store-from-external';

    private function dataValid(): array
    {
        return [
            'judul_kegiatan' => 'KKN Tematik Desa Sukamaju',
            'video_aftermovie' => 'https://youtube.com/watch?v=abcd',
            'artikel_link' => 'https://media.com/artikel-kkn',
            'user_nim' => '2010001',
            'artikel_file' => UploadedFile::fake()->create('artikel.pdf', 100, 'application/pdf'),
        ];
    }

    public function test_menolak_jika_semua_input_kosong(): void
    {
        $this->postJson($this->endpoint, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'judul_kegiatan', 'video_aftermovie', 'artikel_link',
                'artikel_file', 'user_nim',
            ]);
    }

    public function test_menolak_link_video_bukan_url(): void
    {
        $data = $this->dataValid();
        $data['video_aftermovie'] = 'ini-bukan-url';

        $this->post($this->endpoint, $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['video_aftermovie']);
    }

    public function test_menolak_file_artikel_tipe_salah(): void
    {
        $data = $this->dataValid();
        $data['artikel_file'] = UploadedFile::fake()->create('artikel.exe', 100, 'application/octet-stream');

        $this->post($this->endpoint, $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['artikel_file']);
    }

    public function test_status_menolak_nilai_selain_yang_diizinkan(): void
    {
        // Validasi status berjalan sebelum data dicari, jadi id apa pun aman diuji.
        $this->putJson('/api/luaran/1/status', ['status' => 'ngawur'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
