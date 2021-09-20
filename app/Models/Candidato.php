<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nu_cpf_inscrito',
        'dt_nascimento',
    ];

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'candidato_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
