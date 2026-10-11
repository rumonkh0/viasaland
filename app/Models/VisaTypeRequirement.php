<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['country_visa_type_id', 'country', 'visa_type', 'document_category_id', 'is_mandatory'])]
class VisaTypeRequirement extends Model
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
            'is_mandatory' => 'boolean',
        ];
    }

    /**
     * Associated document category.
     */
    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class);
    }

    /**
     * Associated country visa type.
     */
    public function countryVisaType(): BelongsTo
    {
        return $this->belongsTo(CountryVisaType::class);
    }
}
