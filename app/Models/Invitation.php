<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invitation extends Model
{
    public const RELATIONS = ['template', 'people', 'events', 'stories', 'gallery'];

    protected $fillable = [
        'template_id', 'label', 'event_date', 'date_text',
        'intro', 'quote', 'apology', 'thanks', 'cover', 'music',
        'dress_code', 'gift', 'backgrounds', 'extra',
        'status', 'published_at',
    ];

    protected $casts = [
        'event_date'   => 'datetime',
        'published_at' => 'datetime',
        'dress_code'   => 'array',
        'gift'         => 'array',
        'backgrounds'  => 'array',
        'extra'        => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function people(): HasMany
    {
        return $this->hasMany(InvitationPerson::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(InvitationEvent::class)->orderBy('sort');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(InvitationStory::class)->orderBy('sort');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(InvitationGallery::class)->orderBy('sort');
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
