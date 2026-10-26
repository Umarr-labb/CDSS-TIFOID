<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CfUserSkala;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Rekomendasi;
use App\Models\Rule;
use App\Models\RuleDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cdss.local'],
            [
                'name' => 'admin123',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dokter@cdss.local'],
            [
                'name' => 'dokter123',
                'password' => bcrypt('12345678'),
                'role' => 'dokter',
            ]
        );

        User::updateOrCreate(
            ['email' => 'perawat@cdss.local'],
            [
                'name' => 'perawat123',
                'password' => bcrypt('12345678'),
                'role' => 'perawat',
            ]
        );

        foreach ([
            ['label' => 'Tidak yakin', 'nilai_cf' => 0.0],
            ['label' => 'Kurang yakin', 'nilai_cf' => 0.2],
            ['label' => 'Cukup yakin', 'nilai_cf' => 0.4],
            ['label' => 'Yakin', 'nilai_cf' => 0.6],
            ['label' => 'Sangat yakin', 'nilai_cf' => 0.8],
        ] as $skala) {
            CfUserSkala::updateOrCreate(['label' => $skala['label']], $skala);
        }

        $gejala = [
            ['kode_gejala' => 'G01', 'nama_gejala' => 'Demam berkepanjangan', 'jenis' => 'Umum'],
            ['kode_gejala' => 'G02', 'nama_gejala' => 'Sakit kepala', 'jenis' => 'Umum'],
            ['kode_gejala' => 'G03', 'nama_gejala' => 'Mual atau muntah', 'jenis' => 'Pencernaan'],
            ['kode_gejala' => 'G04', 'nama_gejala' => 'Nyeri perut', 'jenis' => 'Pencernaan'],
            ['kode_gejala' => 'G05', 'nama_gejala' => 'Diare atau sembelit', 'jenis' => 'Pencernaan'],
            ['kode_gejala' => 'G06', 'nama_gejala' => 'Nafsu makan menurun', 'jenis' => 'Umum'],
            ['kode_gejala' => 'G07', 'nama_gejala' => 'Tubuh terasa lemas', 'jenis' => 'Umum'],
            ['kode_gejala' => 'G08', 'nama_gejala' => 'Lidah berlapis putih', 'jenis' => 'Pemeriksaan'],
        ];

        $gejalaModels = [];
        foreach ($gejala as $item) {
            $gejalaModels[$item['kode_gejala']] = Gejala::updateOrCreate(
                ['kode_gejala' => $item['kode_gejala']],
                $item
            );
        }

        $penyakit = Penyakit::updateOrCreate(
            ['nama_penyakit' => 'Demam Tifoid'],
            [
                'kode_penyakit' => 'A01.0',
                'nama_penyakit' => 'Demam Tifoid',
                'deskripsi' => 'Infeksi bakteri yang umumnya ditandai demam dan gangguan pencernaan.',
            ]
        );

        foreach (['G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 'G08'] as $kodeGejala) {
            $rule = Rule::updateOrCreate(
                ['kode_rule' => 'R' . substr($kodeGejala, 1)],
                [
                    'id_penyakit' => $penyakit->id_penyakit,
                    'cf_pakar' => 0.7,
                    'keterangan' => 'Gejala pendukung demam tifoid.',
                ]
            );

            RuleDetail::updateOrCreate([
                'id_rule' => $rule->id_rule,
                'id_gejala' => $gejalaModels[$kodeGejala]->id_gejala,
            ]);
        }

        Rekomendasi::updateOrCreate(
            ['id_penyakit' => $penyakit->id_penyakit, 'kategori' => 'Umum'],
            ['isi_rekomendasi' => 'Konsultasikan hasil diagnosis kepada dokter untuk pemeriksaan dan penanganan lebih lanjut.']
        );
    }
}
