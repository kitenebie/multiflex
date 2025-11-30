<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FitnessOffer;
use Illuminate\Auth\Access\HandlesAuthorization;

class FitnessOfferPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FitnessOffer');
    }

    public function view(AuthUser $authUser, FitnessOffer $fitnessOffer): bool
    {
        return $authUser->can('View:FitnessOffer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FitnessOffer');
    }

    public function update(AuthUser $authUser, FitnessOffer $fitnessOffer): bool
    {
        return $authUser->can('Update:FitnessOffer');
    }

    public function delete(AuthUser $authUser, FitnessOffer $fitnessOffer): bool
    {
        return $authUser->can('Delete:FitnessOffer');
    }

    public function restore(AuthUser $authUser, FitnessOffer $fitnessOffer): bool
    {
        return $authUser->can('Restore:FitnessOffer');
    }

    public function forceDelete(AuthUser $authUser, FitnessOffer $fitnessOffer): bool
    {
        return $authUser->can('ForceDelete:FitnessOffer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FitnessOffer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FitnessOffer');
    }

    public function replicate(AuthUser $authUser, FitnessOffer $fitnessOffer): bool
    {
        return $authUser->can('Replicate:FitnessOffer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FitnessOffer');
    }
}