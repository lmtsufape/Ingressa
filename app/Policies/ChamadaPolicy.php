<?php

namespace App\Policies;

use App\Models\Chamada;
use App\Models\User;
use App\Models\DataChamada;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChamadaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Chamada $chamada)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Chamada $chamada)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Chamada $chamada)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Chamada $chamada)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Chamada $chamada)
    {
        //
    }

    /**
     * Determina se o usuário pode enviar os documentos.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function dataEnvio(User $user, Chamada $chamada)
    {
        $data_envio = $chamada->datasChamada()->where('tipo', DataChamada::TIPO_ENUM['envio'])->first();
        $data_reenvio = $chamada->datasChamada()->where('tipo', DataChamada::TIPO_ENUM['reenvio'])->first();

        if ($data_envio && $data_envio->data_inicio <= now() && $data_envio->data_fim >= now()) {
            return true;
        }else if($data_reenvio && $data_reenvio->data_inicio <= now() && $data_reenvio->data_fim >= now()) {
            return true;
        }
        return false;
    }

    public function periodoRetificacao(User $user, Chamada $chamada)
    {
        $data_reenvio = $chamada->datasChamada()->where('tipo', DataChamada::TIPO_ENUM['reenvio'])->first();
        return $data_reenvio && $data_reenvio->data_inicio <= now() && $data_reenvio->data_fim >= now();
    }

    public function periodoEnvio(User $user, Chamada $chamada)
    {
        $data_envio = $chamada->datasChamada()->where('tipo', DataChamada::TIPO_ENUM['envio'])->first();
        if ($data_envio && $data_envio->data_inicio <= now() && $data_envio->data_fim >= now()) {
            return true;
        }
        return false;
    }
}
