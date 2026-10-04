<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'is_predefined', 'is_active', 'sort_order'])]
class DocumentCategory extends Model
{
    use HasFactory;

    /**
     * Get attributes to cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_predefined' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (DocumentCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Scope active categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered categories.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Documents belonging to this category.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Visa type requirements associated with this category.
     */
    public function visaTypeRequirements(): HasMany
    {
        return $this->hasMany(VisaTypeRequirement::class);
    }

    /**
     * Application requirements associated with this category.
     */
    public function applicationRequirements(): HasMany
    {
        return $this->hasMany(ApplicationRequirement::class);
    }
}
