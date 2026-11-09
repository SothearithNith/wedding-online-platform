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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    protected function previewSrc(): Attribute
    {
        return Attribute::get(function (): ?string {
            $path = trim((string) $this->preview_url);

            if ($path === '') {
                return null;
            }

            // External image URL
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            // Normalize stored paths
            $path = ltrim($path, '/');

            // Remove storage/ prefix if already included
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }

            // Generate public storage URL
            return Storage::disk('public')->url($path);
        });
    }
}
