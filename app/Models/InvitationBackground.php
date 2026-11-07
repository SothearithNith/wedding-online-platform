<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationBackground extends Model
{
    protected $fillable = ['template', 'section', 'name', 'path', 'event_type', 'is_default', 'is_active', 'sort'];

    protected $casts = ['is_default' => 'boolean', 'is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
