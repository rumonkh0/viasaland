<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        return $user->isAdmin() || $user->id === $document->user_id;
    }

    /**
     * Determine whether the user can download the document.
     */
    public function download(User $user, Document $document): bool
    {
        return $user->isAdmin() || $user->id === $document->user_id;
    }

    /**
     * Determine whether the user can upload documents to the application.
     */
    public function create(User $user, Application $application): bool
    {
        return $user->isAdmin() || $user->id === $application->user_id;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        return $document->canBeDeletedBy($user);
    }

    /**
     * Determine whether the user can replace the document file.
     */
    public function replace(User $user, Document $document): bool
    {
        return $document->canBeReplacedBy($user);
    }

    /**
     * Determine whether the user can lock the document.
     */
    public function lock(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can unlock the document.
     */
    public function unlock(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can approve/reject the document.
     */
    public function review(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }
}
