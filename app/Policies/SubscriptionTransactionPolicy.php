<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SubscriptionTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionTransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SubscriptionTransaction');
    }

    public function view(AuthUser $authUser, SubscriptionTransaction $subscriptionTransaction): bool
    {
        return $authUser->can('View:SubscriptionTransaction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SubscriptionTransaction');
    }

    public function update(AuthUser $authUser, SubscriptionTransaction $subscriptionTransaction): bool
    {
        return $authUser->can('Update:SubscriptionTransaction');
    }

    public function delete(AuthUser $authUser, SubscriptionTransaction $subscriptionTransaction): bool
    {
        return $authUser->can('Delete:SubscriptionTransaction');
    }

    public function restore(AuthUser $authUser, SubscriptionTransaction $subscriptionTransaction): bool
    {
        return $authUser->can('Restore:SubscriptionTransaction');
    }

    public function forceDelete(AuthUser $authUser, SubscriptionTransaction $subscriptionTransaction): bool
    {
        return $authUser->can('ForceDelete:SubscriptionTransaction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SubscriptionTransaction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SubscriptionTransaction');
    }

    public function replicate(AuthUser $authUser, SubscriptionTransaction $subscriptionTransaction): bool
    {
        return $authUser->can('Replicate:SubscriptionTransaction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SubscriptionTransaction');
    }
}