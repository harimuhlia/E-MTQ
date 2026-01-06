<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration adds a foreign key column to the announcements table
     * allowing each announcement to be associated with a specific event.
     * When an announcement is linked to an event, it should only appear
     * when that event is selected. The column is nullable to maintain
     * backwards compatibility for announcements created before this change.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Add a nullable foreign key to detail_events. Use after() if
            // needed to place the column after user_id for clarity.
            $table->foreignId('detail_event_id')
                ->nullable()
                ->constrained('detail_events')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drop the detail_event_id column and its foreign key constraint.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'detail_event_id')) {
                $table->dropForeign(['detail_event_id']);
                $table->dropColumn('detail_event_id');
            }
        });
    }
};