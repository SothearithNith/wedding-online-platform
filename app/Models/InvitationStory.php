<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationStory extends Model
{
    protected $table = 'invitation_stories';

    protected $fillable = ['year', 'title', 'text', 'sort'];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
