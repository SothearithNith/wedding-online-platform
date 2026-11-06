<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name_en',
        'name_km',
        'slug',
        'icon',
        'route_name',
        'permission_name',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->orderBy('sort_order');
    }

    /**
     * $menu->name returns the label for the current locale,
     * falling back to the other language if one is empty.
     */
    protected function name(): Attribute
    {
        return Attribute::get(function () {
            return app()->getLocale() === 'km'
                ? ($this->name_km ?: $this->name_en)
                : ($this->name_en ?: $this->name_km);
        });
    }

    /**
     * Active top-level menus with their active children, in order.
     */
    public function scopeRootTree(Builder $query): Builder
    {
        return $query
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)])
            ->orderBy('sort_order');
    }
}
