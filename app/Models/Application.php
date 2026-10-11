<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        $requirements = collect();

        // 1. Try matching target_country + visa_type
        if (! empty($this->target_country)) {
            $countryTrimmed = trim($this->target_country);
            $requirements = VisaTypeRequirement::where('visa_type', $this->visa_type)
                ->where(function ($query) use ($countryTrimmed) {
                    $query->whereRaw('LOWER(country) = ?', [strtolower($countryTrimmed)])
                        ->orWhere('country', $countryTrimmed);
                })
                ->get();
        }

        // 2. If no country-specific match, check for global default (where country is null or 'All Countries')
        if ($requirements->isEmpty()) {
            $requirements = VisaTypeRequirement::where('visa_type', $this->visa_type)
                ->where(function ($query) {
                    $query->whereNull('country')
                        ->orWhere('country', '')
                        ->orWhere('country', 'All Countries');
                })
                ->get();
        }

        // 3. If still empty, check for any requirement matching this visa_type
        if ($requirements->isEmpty()) {
            $requirements = VisaTypeRequirement::where('visa_type', $this->visa_type)->get();
        }

        // 4. Fallback to standard core categories if no requirements are configured
        if ($requirements->isEmpty()) {
            $coreCategories = DocumentCategory::whereIn('slug', [
                'passport', 'photograph', 'bank-statement', 'visa-application-form',
            ])->get();

            foreach ($coreCategories as $cat) {
                $this->requirements()->firstOrCreate(
                    ['document_category_id' => $cat->id],
                    [
                        'is_mandatory' => true,
                        'is_fulfilled' => false,
                    ]
                );
            }

            return;
        }

        foreach ($requirements as $req) {
            $this->requirements()->firstOrCreate(
                ['document_category_id' => $req->document_category_id],
                [
                    'is_mandatory' => $req->is_mandatory,
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

    /**
     * Get all organized vault folders for this application.
     * Always ensures an "Others" folder exists for every application.
     *
     * @return Collection<int, array{
     *     id: string,
     *     name: string,
     *     category_id: ?int,
     *     custom_category: ?string,
     *     is_mandatory: bool,
     *     is_fulfilled: bool,
     *     documents: Collection,
     * }>
     */
    public function getVaultFolders(): Collection
    {
        $folders = collect();
        $processedCategoryIds = [];
        $otherCat = DocumentCategory::whereIn('slug', ['other', 'others'])->first();
        $otherCatId = $otherCat?->id;

        // 1. Folders for all configured requirements
        foreach ($this->requirements as $req) {
            $cat = $req->documentCategory;
            if (! $cat) {
                continue;
            }

            $docs = $this->documents->where('document_category_id', $cat->id)->values();
            $processedCategoryIds[] = $cat->id;

            $folders->push([
                'id' => 'cat_'.$cat->id,
                'name' => $cat->name,
                'category_id' => $cat->id,
                'custom_category' => null,
                'is_mandatory' => (bool) $req->is_mandatory,
                'is_fulfilled' => $docs->whereIn('status', ['pending', 'approved'])->isNotEmpty(),
                'documents' => $docs,
            ]);
        }

        // 2. Predefined categories that have documents but were not in requirements (excluding 'Other' category)
        $otherCatDocs = $this->documents->whereNotNull('document_category_id')
            ->whereNotIn('document_category_id', $processedCategoryIds)
            ->filter(fn ($doc) => $doc->document_category_id !== $otherCatId)
            ->groupBy('document_category_id');

        foreach ($otherCatDocs as $catId => $docs) {
            $cat = $docs->first()->documentCategory;
            $name = $cat?->name ?? 'Category #'.$catId;

            $folders->push([
                'id' => 'cat_'.$catId,
                'name' => $name,
                'category_id' => (int) $catId,
                'custom_category' => null,
                'is_mandatory' => false,
                'is_fulfilled' => true,
                'documents' => $docs->values(),
            ]);
        }

        // 3. Custom category folders (user-entered categories, excluding "other" variations)
        $otherKeywords = ['other', 'others', 'general', 'misc', 'miscellaneous'];
        $customDocs = $this->documents->whereNull('document_category_id')
            ->whereNotNull('custom_category')
            ->filter(fn ($doc) => ! in_array(strtolower(trim((string) $doc->custom_category)), $otherKeywords))
            ->groupBy('custom_category');

        foreach ($customDocs as $customName => $docs) {
            $folders->push([
                'id' => 'custom_'.Str::slug($customName),
                'name' => $customName,
                'category_id' => null,
                'custom_category' => $customName,
                'is_mandatory' => false,
                'is_fulfilled' => true,
                'documents' => $docs->values(),
            ]);
        }

        // 4. Gather all documents for the "Others" folder
        $otherDocs = $this->documents->filter(function ($doc) use ($otherCatId, $otherKeywords) {
            $isOtherCat = $otherCatId && $doc->document_category_id === $otherCatId;
            $isOtherCustom = in_array(strtolower(trim((string) $doc->custom_category)), $otherKeywords);
            $isUncategorized = is_null($doc->document_category_id) && empty($doc->custom_category);

            return $isOtherCat || $isOtherCustom || $isUncategorized;
        })->values();

        // 5. Always ensure "Others" folder is present for every application
        $existingOtherIndex = $folders->search(function ($f) use ($otherCatId) {
            return in_array(strtolower($f['name']), ['other', 'others'])
                || ($otherCatId && $f['category_id'] === $otherCatId);
        });

        if ($existingOtherIndex !== false) {
            $existing = $folders[$existingOtherIndex];
            $existing['name'] = 'Others';
            $existing['documents'] = $existing['documents']->concat($otherDocs)->unique('id')->values();
            $existing['is_fulfilled'] = $existing['documents']->isNotEmpty();
            $folders[$existingOtherIndex] = $existing;
        } else {
            $folders->push([
                'id' => 'folder_others',
                'name' => 'Others',
                'category_id' => $otherCatId,
                'custom_category' => 'Others',
                'is_mandatory' => false,
                'is_fulfilled' => $otherDocs->isNotEmpty(),
                'documents' => $otherDocs,
            ]);
        }

        return $folders;
    }
}
