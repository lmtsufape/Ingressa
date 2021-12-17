<?php

namespace App\Policies;

use App\Models\TipoAnalista;
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

    public function isAdminOrAnalistaGeral(User $user)
    {
        return $this->ehAnalistaGeral($user) || $this->isAdmin($user);
    }

    public function ehAnalistaGeral(User $user) 
    {
        if ($user->role == User::ROLE_ENUM['analista']) {
            return $user->tipo_analista()->where('tipo', TipoAnalista::TIPO_ENUM['geral'])->get()->count()  > 0;
        }

        return false;
    }

    public function ehAnalistaHeteroidentificacao(User $user) 
    {
        if ($user->role == User::ROLE_ENUM['analista']) {
            return $user->tipo_analista()->where('tipo', TipoAnalista::TIPO_ENUM['heteroidentificacao'])->get()->count()  > 0;
        }

        return false;
    }

    public function ehAnalistaMedico(User $user) 
    {
        if ($user->role == User::ROLE_ENUM['analista']) {
            return $user->tipo_analista()->where('tipo', TipoAnalista::TIPO_ENUM['medico'])->get()->count()  > 0;
        }

        return false;
    }

}
