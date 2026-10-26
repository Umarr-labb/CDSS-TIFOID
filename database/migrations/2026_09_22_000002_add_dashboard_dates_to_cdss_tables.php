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
        Schema::table('konsultasi', function (Blueprint $table) {
            $table->dateTime('tanggal')->nullable()->after('id_pasien');
        });

        Schema::table('pasien', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('no_telp');
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });

        Schema::table('konsultasi', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
};
