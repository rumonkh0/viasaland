<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'visa_type', 'target_country', 'status', 'notes'])]
class Application extends Model
{
    use HasFactory;

    /**
     * The owner of the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All documents uploaded in this application's vault.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Required document categories for this application.
     */
    public function requirements(): HasMany
    {
        return $this->hasMany(ApplicationRequirement::class);
    }

    /**
     * Calculate total document count.
     */
    public function documentCount(): int
    {
        return $this->documents()->count();
    }

    /**
     * Calculate total vault storage size in bytes.
     */
    public function vaultSizeBytes(): int
    {
        return (int) $this->documents()->sum('file_size');
    }

    /**
     * Format vault storage size.
     */
    public function vaultSizeFormatted(): string
    {
        $bytes = $this->vaultSizeBytes();
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }

    /**
     * Sync default requirements for the visa type into this application.
     */
    public function syncRequirementsFromVisaType(): void
    {
        $defaults = VisaTypeRequirement::where('visa_type', $this->visa_type)->get();

        foreach ($defaults as $defaultReq) {
            $this->requirements()->firstOrCreate(
                ['document_category_id' => $defaultReq->document_category_id],
                [
                    'is_mandatory' => $defaultReq->is_mandatory,
                    'is_fulfilled' => false,
                ]
            );
        }
    }

    /**
     * Recheck and refresh fulfillment status of requirements.
     */
    public function refreshRequirementStatuses(): void
    {
        $requirements = $this->requirements()->get();

        foreach ($requirements as $requirement) {
            $hasApprovedOrSubmitted = $this->documents()
                ->where('document_category_id', $requirement->document_category_id)
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($requirement->is_fulfilled !== $hasApprovedOrSubmitted) {
                $requirement->update(['is_fulfilled' => $hasApprovedOrSubmitted]);
            }
        }
    }

    /**
     * Percentage of mandatory requirements fulfilled.
     */
    public function completionPercentage(): float
    {
        $mandatoryCount = $this->requirements()->where('is_mandatory', true)->count();
        if ($mandatoryCount === 0) {
            return 100.0;
        }

        $fulfilledCount = $this->requirements()
            ->where('is_mandatory', true)
            ->where('is_fulfilled', true)
            ->count();

        return round(($fulfilledCount / $mandatoryCount) * 100, 1);
    }
}
