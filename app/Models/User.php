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
        $this->role = User::ROLE_ENUM['analista'];
        $this->primeiro_acesso = false;
    }

    public function candidato()
    {
        return $this->hasOne(Candidato::class, 'user_id');
    }
}
