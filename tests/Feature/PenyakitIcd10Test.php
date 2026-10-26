<?php

namespace Tests\Feature;

use App\Models\Penyakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenyakitIcd10Test extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_a_disease_with_an_icd10_code(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('penyakit.store'), [
            'kode_penyakit' => 'A01.0',
            'nama_penyakit' => 'Demam Tifoid',
            'deskripsi' => 'Infeksi bakteri.',
        ]);

        $response->assertRedirect(route('penyakit.index'));
        $this->assertDatabaseHas('penyakit', [
            'kode_penyakit' => 'A01.0',
            'nama_penyakit' => 'Demam Tifoid',
        ]);
    }

    public function test_admin_cannot_save_a_code_with_invalid_icd10_format(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->from(route('penyakit.create'))->post(route('penyakit.store'), [
            'kode_penyakit' => 'P1',
            'nama_penyakit' => 'Demam Tifoid',
            'deskripsi' => 'Infeksi bakteri.',
        ]);

        $response->assertRedirect(route('penyakit.create'));
        $response->assertSessionHasErrors([
            'kode_penyakit' => 'Kode harus menggunakan format ICD-10, contoh A01.0.',
        ]);
        $this->assertDatabaseMissing('penyakit', ['nama_penyakit' => 'Demam Tifoid']);
    }
}
