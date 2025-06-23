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

    public function student(User $user, User $model): bool
    {
        $student = $model->student;

        if ($user->hasRole('user')) return $user->id === $model->id && $student;

        return $student;
    }
}
