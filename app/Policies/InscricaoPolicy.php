<?php

namespace App\Policies;

use App\Models\Inscricao;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InscricaoPolicy
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

    public function isCandidatoDono(User $user, Inscricao $inscricao)
    {
        $userPolicy = new UserPolicy();
        if ($userPolicy->isAdmin($user)) {
            return true;
        }elseif($userPolicy->isCandidato($user) && $inscricao->candidato->user->id == $user->id){
            return true;
        }else{
            return false;
        }
    }

    public function isCandidatoDonoOrAnalista(User $user, Inscricao $inscricao)
    {
        $userPolicy = new UserPolicy();
        return $this->isCandidatoDono($user, $inscricao) || $userPolicy->isAnalista($user);
    }
}
