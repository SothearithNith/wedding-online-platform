<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'preview_url',
        'schema',
        'sample_data',
        'is_active',
        'is_public',
        'sort',
    ];

    protected $casts = [
        'schema'      => 'array',
        'sample_data' => 'array',
        'is_active'   => 'boolean',
        'is_public'   => 'boolean',
    ];

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    // External URLs pass through; uploaded files come from the "public" disk
    protected function previewSrc(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->preview_url) {
                return null;
            }

            return str_starts_with($this->preview_url, 'http')
                ? $this->preview_url
                : Storage::disk('public')->url($this->preview_url);
        });
    }
}
