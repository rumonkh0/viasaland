<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'code', 'flag', 'sort_order', 'is_active'])]
class Country extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Country $country) {
            if (empty($country->slug)) {
                $country->slug = Str::slug($country->name);
            }
        });
    }

    /**
     * Visa types available under this country.
     */
    public function visaTypes(): HasMany
    {
        return $this->hasMany(CountryVisaType::class)->orderBy('sort_order')->orderBy('name');
    }

    /**
     * All requirements across all visa types for this country.
     */
    public function requirements(): HasManyThrough
    {
        return $this->hasManyThrough(VisaTypeRequirement::class, CountryVisaType::class);
    }

    /**
     * Formatted title with flag if available.
     */
    public function getDisplayNameAttribute(): string
    {
        return ($this->flag ? $this->flag.' ' : '').$this->name;
    }
}
