<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationPerson extends Model
{
    protected $table = 'invitation_people';

    protected $fillable = ['role', 'name', 'parents', 'photo'];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
