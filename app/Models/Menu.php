<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'name_en',
        'name_km',
        'slug',
        'icon',
        'route_name',
        'permission_name',
        'sort_order',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    // $menu->name returns the Khmer or English name based on the current locale
    protected function name(): Attribute
    {
        return Attribute::get(
            fn() => app()->getLocale() === 'km'
                ? ($this->name_km ?: $this->name_en)
                : $this->name_en
        );
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** Active children only. Used by the sidebar and Apply Permission. */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /** Every child, including inactive. Used by the Features admin page. */
    public function allChildren(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopeRootTree($query)
    {
        return $query->whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('sort_order');
    }
}
