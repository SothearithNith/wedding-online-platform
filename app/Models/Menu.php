<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
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

    protected $casts = ['is_active' => 'boolean'];

    // $menu->name returns the Khmer or English name based on the current locale
    protected function name(): Attribute
    {
        return Attribute::get(
            fn() => app()->getLocale() === 'km'
                ? ($this->name_km ?: $this->name_en)
                : $this->name_en
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function scopeRootTree($query)
    {
        return $query->whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('sort_order');
    }
}
