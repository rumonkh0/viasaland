<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Determine whether the user can view any applications.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the application vault.
     */
    public function view(User $user, Application $application): bool
    {
        return $user->isAdmin() || $user->id === $application->user_id;
    }

    /**
     * Determine whether the user can create applications.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the application.
     */
    public function update(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the application.
     */
    public function delete(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can bulk delete applications.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can download ZIP archive of application documents.
     */
    public function downloadZip(User $user, Application $application): bool
    {
        return $user->isAdmin() || $user->id === $application->user_id;
    }
}
