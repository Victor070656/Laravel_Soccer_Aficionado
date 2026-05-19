<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;

class CommunityPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Community $community): bool
    {
        return $community->is_active || ($user && $user->isAdmin());
    }

    public function create(User $user): bool
    {
        return ! $user->is_banned;
    }

    public function update(User $user, Community $community): bool
    {
        return $user->id === $community->created_by
            || $community->isModerator($user)
            || $user->isAdmin();
    }

    public function delete(User $user, Community $community): bool
    {
        return $user->id === $community->created_by || $user->isAdmin();
    }
}
