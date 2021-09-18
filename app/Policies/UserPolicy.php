<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function isCandidato(User $user)
    {
        return $user->role == User::ROLE_ENUM['candidato'];
    }

    public function isAnalista(User $user)
    {
        return $user->role == User::ROLE_ENUM['analista'];
    }

    public function isAdmin(User $user)
    {
        return $user->role == User::ROLE_ENUM['admin'];
    }

    public function isAdminOrAnalista(User $user)
    {
        return $this->isAnalista($user) || $this->isAdmin($user);
    }

}
