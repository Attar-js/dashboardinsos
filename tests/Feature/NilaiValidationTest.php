<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Tes validasi backend untuk endpoint penerimaan nilai dari project-akhir.
 * Memastikan input sembarangan ditolak (422) sebelum tersimpan ke database.
 *
 * Jalankan:  php artisan test --filter=NilaiValidationTest
 */
class NilaiValidationTest extends TestCase
{
    private string $endpoint = '/nilai-akhir/api/receive-nilai';

    private function dataValid(): array
    {
        return [
            'mahasiswa_nim' => '2010001',
            'dosen_id' => 3,
            'nilai_akhir' => 85.5,
        ];
    }

    public function test_menolak_jika_field_wajib_kosong(): void
    {
        $this->postJson($this->endpoint, [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['mahasiswa_nim', 'dosen_id', 'nilai_akhir']);
    }

    public function test_menolak_nilai_akhir_lebih_dari_100(): void
    {
        $data = $this->dataValid();
        $data['nilai_akhir'] = 150;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nilai_akhir']);
    }

    public function test_menolak_nilai_akhir_negatif(): void
    {
        $data = $this->dataValid();
        $data['nilai_akhir'] = -5;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nilai_akhir']);
    }

    public function test_menolak_dosen_id_bukan_angka(): void
    {
        $data = $this->dataValid();
        $data['dosen_id'] = 'bukan-angka';

        $this->postJson($this->endpoint, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['dosen_id']);
    }
}
