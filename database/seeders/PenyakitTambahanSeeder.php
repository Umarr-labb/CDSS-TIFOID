<?php

namespace Database\Seeders;

use App\Models\Penyakit;
use Illuminate\Database\Seeder;

class PenyakitTambahanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'kode_penyakit' => 'A01.0',
                'nama_penyakit' => 'Demam Tifoid',
                'deskripsi' => 'Infeksi bakteri yang umumnya ditandai demam dan gangguan pencernaan.',
            ],
            [
                'kode_penyakit' => 'A91',
                'nama_penyakit' => 'Demam Berdarah',
                'deskripsi' => 'Demam berdarah dengue (dengue haemorrhagic fever), penyakit akibat infeksi virus dengue.',
            ],
            [
                'kode_penyakit' => 'B54',
                'nama_penyakit' => 'Malaria',
                'deskripsi' => 'Malaria, tidak diklasifikasikan lebih lanjut; penyakit akibat parasit Plasmodium.',
            ],
            [
                'kode_penyakit' => 'A90',
                'nama_penyakit' => 'Demam Dengue',
                'deskripsi' => 'Demam dengue klasik (classical dengue), penyakit akibat infeksi virus dengue.',
            ],
        ] as $data) {
            Penyakit::updateOrCreate(
                ['nama_penyakit' => $data['nama_penyakit']],
                [
                    'kode_penyakit' => $data['kode_penyakit'],
                    'deskripsi' => $data['deskripsi'],
                ]
            );
        }
    }
}
