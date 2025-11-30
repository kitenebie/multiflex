<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CoachHandle;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoachHandlePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CoachHandle');
    }

    public function view(AuthUser $authUser, CoachHandle $coachHandle): bool
    {
        return $authUser->can('View:CoachHandle');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CoachHandle');
    }

    public function update(AuthUser $authUser, CoachHandle $coachHandle): bool
    {
        return $authUser->can('Update:CoachHandle');
    }

    public function delete(AuthUser $authUser, CoachHandle $coachHandle): bool
    {
        return $authUser->can('Delete:CoachHandle');
    }

    public function restore(AuthUser $authUser, CoachHandle $coachHandle): bool
    {
        return $authUser->can('Restore:CoachHandle');
    }

    public function forceDelete(AuthUser $authUser, CoachHandle $coachHandle): bool
    {
        return $authUser->can('ForceDelete:CoachHandle');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CoachHandle');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CoachHandle');
    }

    public function replicate(AuthUser $authUser, CoachHandle $coachHandle): bool
    {
        return $authUser->can('Replicate:CoachHandle');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CoachHandle');
    }
}