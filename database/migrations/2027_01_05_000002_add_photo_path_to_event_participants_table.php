<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds a column to store the path of the participant's
     * selfie photo (with red background) in the event_participants table.
     * The column is nullable because participants may not have uploaded
     * the photo yet.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('event_participants', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('ktp_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops the photo_path column from event_participants.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('event_participants', function (Blueprint $table) {
            if (Schema::hasColumn('event_participants', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
        });
    }
};