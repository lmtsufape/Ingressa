<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Models\Inscricao;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public const ROLE_ENUM = [
        'admin'     => 1,
        'analista'  => 2,
        'candidato' => 3,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'primeiro_acesso'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function setAtributes($input)
    {
        $this->name = $input['name'];
        $this->email = $input['email'];
        $this->password = Hash::make($input['password']);
    }

    public function candidato()
    {
        return $this->hasOne(Candidato::class, 'user_id');
    }

    public function tipo_analista()
    {
        return $this->belongsToMany(TipoAnalista::class, 'tipo_analista_user', 'user_id', 'tipo_analista_id');
    }

    public static function analistasGeral() 
    {
        $analistas = collect();
        $users = User::where('role', User::ROLE_ENUM['analista'])->get();
        
        foreach ($users as $analista) {
            if ($analista->tipo_analista()->where('tipo', TipoAnalista::TIPO_ENUM['geral'])->get()->count() > 0) {
                $analistas->push($analista);
            }
        }

        return $analistas;
    }

    public static function analistasHeteroidentificacao()
    {
        $heteroidentificacao = collect();
        $analistas = User::where('role', User::ROLE_ENUM['analista'])->get();

        foreach ($analistas as $analista) {
            if ($analista->tipo_analista()->where('tipo', TipoAnalista::TIPO_ENUM['heteroidentificacao'])->get()->count() > 0) {
                $heteroidentificacao->push($analista);
            }
        }

        return $heteroidentificacao;
    }

    

    public static function gerar_user_inscricao(Inscricao $inscricao) 
    {
        $user = $inscricao->candidato->user;
        $user->email = $inscricao->ds_email;
        return $user;
    }
}
