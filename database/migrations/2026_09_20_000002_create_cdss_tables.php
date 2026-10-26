<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->bigIncrements('id_pasien');
            $table->string('no_rm')->unique();
            $table->string('nama_pasien');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->integer('umur')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
        });

        Schema::create('gejala', function (Blueprint $table) {
            $table->bigIncrements('id_gejala');
            $table->string('kode_gejala')->unique();
            $table->string('nama_gejala');
            $table->string('jenis')->nullable();
            $table->text('keterangan')->nullable();
        });

        Schema::create('penyakit', function (Blueprint $table) {
            $table->bigIncrements('id_penyakit');
            $table->string('kode_penyakit')->unique();
            $table->string('nama_penyakit');
            $table->text('deskripsi')->nullable();
        });

        Schema::create('rule', function (Blueprint $table) {
            $table->bigIncrements('id_rule');
            $table->string('kode_rule')->unique();
            $table->unsignedBigInteger('id_penyakit');
            $table->double('cf_pakar', 8, 4)->default(0);
            $table->text('keterangan')->nullable();

            $table->foreign('id_penyakit')->references('id_penyakit')->on('penyakit')->cascadeOnDelete();
        });

        Schema::create('rule_detail', function (Blueprint $table) {
            $table->bigIncrements('id_rule_detail');
            $table->unsignedBigInteger('id_rule');
            $table->unsignedBigInteger('id_gejala');

            $table->foreign('id_rule')->references('id_rule')->on('rule')->cascadeOnDelete();
            $table->foreign('id_gejala')->references('id_gejala')->on('gejala')->cascadeOnDelete();
        });

        Schema::create('cf_user_skala', function (Blueprint $table) {
            $table->bigIncrements('id_skala');
            $table->string('label');
            $table->double('nilai_cf', 8, 4)->default(0);
        });

        Schema::create('konsultasi', function (Blueprint $table) {
            $table->bigIncrements('id_konsultasi');
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_penyakit_hasil')->nullable();
            $table->double('cf_hasil', 8, 4)->nullable();
            $table->string('status')->default('proses');
            $table->text('catatan')->nullable();

            $table->foreign('id_pasien')->references('id_pasien')->on('pasien')->cascadeOnDelete();
            $table->foreign('id_penyakit_hasil')->references('id_penyakit')->on('penyakit')->nullOnDelete();
        });

        Schema::create('konsultasi_detail', function (Blueprint $table) {
            $table->bigIncrements('id_detail');
            $table->unsignedBigInteger('id_konsultasi');
            $table->unsignedBigInteger('id_gejala');
            $table->double('nilai_cf_user', 8, 4)->default(0);

            $table->foreign('id_konsultasi')->references('id_konsultasi')->on('konsultasi')->cascadeOnDelete();
            $table->foreign('id_gejala')->references('id_gejala')->on('gejala')->cascadeOnDelete();
        });

        Schema::create('hasil_diagnosis', function (Blueprint $table) {
            $table->bigIncrements('id_hasil');
            $table->unsignedBigInteger('id_konsultasi');
            $table->unsignedBigInteger('id_penyakit');
            $table->double('cf_gabungan', 8, 4)->default(0);
            $table->double('persentase', 8, 4)->default(0);
            $table->string('kategori')->nullable();

            $table->foreign('id_konsultasi')->references('id_konsultasi')->on('konsultasi')->cascadeOnDelete();
            $table->foreign('id_penyakit')->references('id_penyakit')->on('penyakit')->cascadeOnDelete();
        });

        Schema::create('log_cf', function (Blueprint $table) {
            $table->bigIncrements('id_log');
            $table->unsignedBigInteger('id_konsultasi');
            $table->unsignedBigInteger('id_rule');
            $table->unsignedBigInteger('id_penyakit');
            $table->double('cf_pakar', 8, 4)->default(0);
            $table->double('cf_user_min', 8, 4)->default(0);
            $table->double('cf_evidence', 8, 4)->default(0);
            $table->double('cf_sebelum', 8, 4)->nullable();
            $table->double('cf_sesudah', 8, 4)->nullable();

            $table->foreign('id_konsultasi')->references('id_konsultasi')->on('konsultasi')->cascadeOnDelete();
            $table->foreign('id_rule')->references('id_rule')->on('rule')->cascadeOnDelete();
            $table->foreign('id_penyakit')->references('id_penyakit')->on('penyakit')->cascadeOnDelete();
        });

        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->bigIncrements('id_rekomendasi');
            $table->unsignedBigInteger('id_penyakit');
            $table->string('kategori')->nullable();
            $table->text('isi_rekomendasi')->nullable();

            $table->foreign('id_penyakit')->references('id_penyakit')->on('penyakit')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
        Schema::dropIfExists('log_cf');
        Schema::dropIfExists('hasil_diagnosis');
        Schema::dropIfExists('konsultasi_detail');
        Schema::dropIfExists('konsultasi');
        Schema::dropIfExists('cf_user_skala');
        Schema::dropIfExists('rule_detail');
        Schema::dropIfExists('rule');
        Schema::dropIfExists('penyakit');
        Schema::dropIfExists('gejala');
        Schema::dropIfExists('pasien');
    }
};
