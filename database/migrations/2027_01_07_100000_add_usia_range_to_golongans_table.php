<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds `usia_min` and `usia_max` integer columns to the
     * `golongans` table. These columns represent the minimum and maximum age
     * (in years) allowed for participants in a given golongan. Both columns
     * are nullable to allow flexibility when no explicit limit is set. When
     * `usia_max` is null, the legacy `max_usia` column may still be used as
     * a fallback by application logic.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('golongans', function (Blueprint $table) {
            // Tambah kolom usia_min setelah max_usia jika belum ada
            if (!Schema::hasColumn('golongans', 'usia_min')) {
                $table->integer('usia_min')->nullable()->after('max_usia');
            }
            // Tambah kolom usia_max setelah usia_min jika belum ada
            if (!Schema::hasColumn('golongans', 'usia_max')) {
                $table->integer('usia_max')->nullable()->after('usia_min');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('golongans', function (Blueprint $table) {
            if (Schema::hasColumn('golongans', 'usia_max')) {
                $table->dropColumn('usia_max');
            }
            if (Schema::hasColumn('golongans', 'usia_min')) {
                $table->dropColumn('usia_min');
            }
        });
    }
};