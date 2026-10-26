<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_telp')->nullable();
            $table->string('spesialisasi')->nullable();
        });

        // Changing column type/default requires Doctrine DBAL on SQLite.
        // On MySQL we keep the stricter enum definition; on SQLite a plain
        // string column is sufficient for application logic.
        if (DB::getDriverName() === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'dokter', 'perawat'])
                    ->default('perawat')
                    ->after('password')
                    ->change();
                $table->string('no_telp')->nullable()->after('role')->change();
                $table->string('spesialisasi')->nullable()->after('no_telp')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_telp', 'spesialisasi']);
        });

        if (DB::getDriverName() === 'mysql') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('admin')->after('email')->change();
            });
        }
    }
};
