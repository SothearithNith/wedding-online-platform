<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = [
        'key', 'name', 'description', 'preview_url', 'schema', 'sample_data',
        'is_active', 'is_public', 'sort',
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
}
