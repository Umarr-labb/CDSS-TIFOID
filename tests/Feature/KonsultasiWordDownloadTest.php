<?php

namespace Tests\Feature;

use App\Models\HasilDiagnosis;
use App\Models\Konsultasi;
use App\Models\Pasien;
use App\Models\Penyakit;
use App\Models\Rekomendasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class KonsultasiWordDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_download_consultation_result_as_word_document(): void
    {
        $user = User::factory()->create(['role' => 'dokter']);
        $pasien = Pasien::create([
            'no_rm' => 'RM-001',
            'nama_pasien' => 'Pasien & Uji',
            'jenis_kelamin' => 'L',
            'umur' => 25,
        ]);
        $penyakit = Penyakit::create([
            'kode_penyakit' => 'P01',
            'nama_penyakit' => 'Demam Tifoid',
            'deskripsi' => 'Diagnosis uji',
        ]);
        $konsultasi = Konsultasi::create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal' => now(),
            'id_penyakit_hasil' => $penyakit->id_penyakit,
            'cf_hasil' => 0.85,
            'status' => 'selesai',
        ]);
        HasilDiagnosis::create([
            'id_konsultasi' => $konsultasi->id_konsultasi,
            'id_penyakit' => $penyakit->id_penyakit,
            'cf_gabungan' => 0.85,
            'persentase' => 85,
            'kategori' => 'Kuat',
        ]);
        Rekomendasi::create([
            'id_penyakit' => $penyakit->id_penyakit,
            'kategori' => 'Pemeriksaan',
            'isi_rekomendasi' => 'Lakukan pemeriksaan lanjutan.',
        ]);

        $response = $this->actingAs($user)
            ->get(route('konsultasi.hasil.word', $konsultasi->id_konsultasi));

        $response->assertDownload('hasil-diagnosis-' . $konsultasi->id_konsultasi . '.docx');
        $response->assertHeader(
            'Content-Type',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        $filePath = $response->baseResponse->getFile()->getPathname();
        $archive = new ZipArchive();
        $this->assertTrue($archive->open($filePath));
        $document = $archive->getFromName('word/document.xml');
        $archive->close();
        unlink($filePath);

        $this->assertIsString($document);
        $this->assertNotFalse(simplexml_load_string($document));
        $this->assertStringContainsString('Pasien &amp; Uji', $document);
        $this->assertStringContainsString('Demam Tifoid', $document);
        $this->assertStringContainsString('Lakukan pemeriksaan lanjutan.', $document);
    }

    public function test_word_download_requires_authentication(): void
    {
        $response = $this->get(route('konsultasi.hasil.word', 1));

        $response->assertRedirect(route('login'));
    }
}
