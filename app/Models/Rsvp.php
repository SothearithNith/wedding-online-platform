<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rsvp extends Model
{
    protected $table = 'rsvps';

    protected $fillable = ['guest_name', 'attending', 'guests_count', 'message', 'ip'];

    protected $casts = ['attending' => 'boolean'];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
