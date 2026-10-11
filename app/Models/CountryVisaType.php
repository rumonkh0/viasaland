<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['country_id', 'name', 'display_name', 'slug', 'sort_order', 'is_active'])]
class CountryVisaType extends Model
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
        static::creating(function (CountryVisaType $type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
            if (empty($type->display_name)) {
                $type->display_name = Str::endsWith($type->name, 'Visa') ? $type->name : $type->name.' Visa';
            }
        });
    }

    /**
     * Parent country.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Requirements defined for this country visa type.
     */
    public function requirements(): HasMany
    {
        return $this->hasMany(VisaTypeRequirement::class, 'country_visa_type_id');
    }
}
