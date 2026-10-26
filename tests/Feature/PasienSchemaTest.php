<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PasienSchemaTest extends TestCase
{
    public function test_required_cdss_tables_exist(): void
    {
        $this->assertTrue(Schema::hasTable('pasien'));
        $this->assertTrue(Schema::hasTable('gejala'));
        $this->assertTrue(Schema::hasTable('penyakit'));
        $this->assertTrue(Schema::hasTable('rule'));
        $this->assertTrue(Schema::hasTable('rule_detail'));
        $this->assertTrue(Schema::hasTable('cf_user_skala'));
        $this->assertTrue(Schema::hasTable('konsultasi'));
        $this->assertTrue(Schema::hasTable('konsultasi_detail'));
        $this->assertTrue(Schema::hasTable('hasil_diagnosis'));
        $this->assertTrue(Schema::hasTable('log_cf'));
        $this->assertTrue(Schema::hasTable('rekomendasi'));
    }
}
