<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'source',
        'disk',
        'path',
        'original_url',
        'resource_type',
        'mime',
        'size',
        'name',
        'alt',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): ?string
    {
        // External image
        if ($this->source === 'external') {
            return $this->original_url;
        }

        // Uploaded image
        if (!$this->disk || !$this->path) {
            return null;
        }

        return Storage::disk($this->disk)->url($this->path);
    }

    public function isExternal(): bool
    {
        return $this->source === 'external';
    }

    public function isUploaded(): bool
    {
        return $this->source === 'upload';
    }
}
