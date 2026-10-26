<?php

namespace Tests\Feature;

use Tests\TestCase;

class PwaInstallTest extends TestCase
{
    public function test_login_page_offers_pwa_installation_and_registers_service_worker(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('manifest.webmanifest');
        $response->assertSee('Pasang Aplikasi');
        $response->assertSee('service-worker.js');
    }

    public function test_pwa_manifest_and_offline_shell_assets_are_available(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.webmanifest')), true);

        $this->assertSame('CDSS Tifoid', $manifest['name']);
        $this->assertSame('standalone', $manifest['display']);
        $this->assertContains('/icons/icon-192.png', array_column($manifest['icons'], 'src'));
        $this->assertContains('/icons/icon-512.png', array_column($manifest['icons'], 'src'));
        $this->assertFileExists(public_path('service-worker.js'));
        $this->assertFileExists(public_path('offline.html'));
        $this->assertFileExists(public_path('pwa-icon.svg'));
        $this->assertFileExists(public_path('icons/icon-192.png'));
        $this->assertFileExists(public_path('icons/icon-512.png'));
        $this->assertSame([192, 192], array_slice(getimagesize(public_path('icons/icon-192.png')), 0, 2));
        $this->assertSame([512, 512], array_slice(getimagesize(public_path('icons/icon-512.png')), 0, 2));
    }
}
