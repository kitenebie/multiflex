<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SystemLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SystemLog');
    }

    public function view(AuthUser $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('View:SystemLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SystemLog');
    }

    public function update(AuthUser $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Update:SystemLog');
    }

    public function delete(AuthUser $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Delete:SystemLog');
    }

    public function restore(AuthUser $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Restore:SystemLog');
    }

    public function forceDelete(AuthUser $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('ForceDelete:SystemLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SystemLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SystemLog');
    }

    public function replicate(AuthUser $authUser, SystemLog $systemLog): bool
    {
        return $authUser->can('Replicate:SystemLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SystemLog');
    }
}