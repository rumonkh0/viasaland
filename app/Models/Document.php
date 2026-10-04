<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id',
    'application_id',
    'document_category_id',
    'custom_category',
    'file_path',
    'file_name',
    'file_size',
    'mime_type',
    'status',
    'is_locked',
    'locked_by',
    'locked_at',
    'admin_notes',
    'uploaded_by',
    'version',
])]
class Document extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Attributes cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
            'locked_at' => 'datetime',
            'file_size' => 'integer',
            'version' => 'integer',
        ];
    }

    /**
     * Owner of document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Associated application (vault folder).
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Assigned category.
     */
    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    /**
     * User who uploaded this file.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Admin who locked this document.
     */
    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /**
     * Historical previous versions of this document.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version', 'desc');
    }

    /**
     * Activity log for this document.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(DocumentActivity::class)->latest();
    }

    /**
     * Check if file is locked by admin.
     */
    public function isLocked(): bool
    {
        return (bool) $this->is_locked;
    }

    /**
     * Determine if given user can delete this document.
     */
    public function canBeDeletedBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $this->user_id && ! $this->isLocked();
    }

    /**
     * Determine if given user can replace this document.
     */
    public function canBeReplacedBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $this->user_id && ! $this->isLocked();
    }

    /**
     * Display name for category.
     */
    public function categoryName(): string
    {
        return $this->documentCategory?->name ?? $this->custom_category ?? 'Other';
    }

    /**
     * Format file size.
     */
    public function fileSizeFormatted(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
