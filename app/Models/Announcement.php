<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        // Associate announcement with a specific detail event.
        'detail_event_id',
    ];

    /**
     * Get the user (creator) that made the announcement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event associated with this announcement.
     *
     * Each announcement may belong to a single detail event. If the
     * announcement is not tied to any event (detail_event_id is null),
     * it will not appear in any event-specific listing.
     */
    public function detailEvent()
    {
        return $this->belongsTo(DetailEvent::class, 'detail_event_id');
    }
}