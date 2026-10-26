<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $codesByDisease = [
            'Demam Tifoid' => 'A01.0',
            'Demam Berdarah' => 'A91',
            'Malaria' => 'B54',
            'Demam Dengue' => 'A90',
        ];

        foreach ($codesByDisease as $name => $code) {
            $codeInUse = DB::table('penyakit')
                ->where('kode_penyakit', $code)
                ->where('nama_penyakit', '!=', $name)
                ->exists();

            if ($codeInUse) {
                throw new \RuntimeException("Kode ICD-10 {$code} sudah digunakan oleh penyakit lain.");
            }

            DB::table('penyakit')
                ->where('nama_penyakit', $name)
                ->update(['kode_penyakit' => $code]);
        }
    }

    public function down(): void
    {
        // Keep the corrected international codes; the previous values are not reliably known.
    }
};
