<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $user, User $model): bool
    {
        return !$user->hasRole('user') && $model->admin;
    }

    public function update(User $user, User $model): bool
    {
        return !$user->hasRole('user') && $model->admin;
    }

    public function destroy(User $user, User $model): bool
    {
        return !$user->hasRole('user') && $model->admin;
    }
}
