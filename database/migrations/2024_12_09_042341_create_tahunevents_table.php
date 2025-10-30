<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The tahunevents table has been removed. Event years are now derived from
        // the detail_events table (via pendaftaran_mulai or waktu_pelaksanaan_mulai).
        // Drop the table if it still exists from a previous version to avoid errors.
        Schema::dropIfExists('tahunevents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the table if it exists. Since the table is no longer created,
        // this call is safe and simply ensures clean rollback.
        Schema::dropIfExists('tahunevents');
    }
};
