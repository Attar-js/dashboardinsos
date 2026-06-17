<?php

namespace Tests\Unit;

use App\Helpers\DashboardHelper;
use Tests\TestCase;

/**
 * Unit test untuk kelas DashboardHelper.
 *
 * Berbeda dengan Feature test: di sini kita TIDAK mengirim request HTTP.
 * Kita memanggil method kelas secara langsung, lalu memeriksa nilai
 * yang dikembalikan. Ini menguji logika penggabungan URL secara terisolasi.
 *
 * Cara menjalankan:  php artisan test --filter=DashboardHelperTest
 */
class DashboardHelperTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tetapkan nilai config agar hasil tes konsisten, tidak bergantung .env.
        config([
            'app.dashboard_url' => 'http://localhost:8001',
            'app.landing_url' => 'http://localhost:8000',
        ]);
    }

    public function test_url_dashboard_tanpa_path(): void
    {
        $this->assertSame(
            'http://localhost:8001',
            DashboardHelper::getDashboardUrl()
        );
    }

    public function test_url_dashboard_dengan_path(): void
    {
        $this->assertSame(
            'http://localhost:8001/special-pages/proposal',
            DashboardHelper::getDashboardUrl('special-pages/proposal')
        );
    }

    public function test_path_dengan_garis_miring_di_depan_dirapikan(): void
    {
        // Path diawali "/" tetap menghasilkan URL yang benar (tidak jadi "//").
        $this->assertSame(
            'http://localhost:8001/nilai-akhir',
            DashboardHelper::getDashboardUrl('/nilai-akhir')
        );
    }

    public function test_url_api_menambahkan_prefix_api(): void
    {
        $this->assertSame(
            'http://localhost:8001/api/kkn/pendaftar',
            DashboardHelper::getApiUrl('kkn/pendaftar')
        );
    }

    public function test_url_landing_form_kkn(): void
    {
        $this->assertSame(
            'http://localhost:8000/kkn/form',
            DashboardHelper::getFormUrl()
        );
    }
}
