<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidato_id',
        'chamada_id',
        'protocolo',
    ];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'candidato_id');
    }

    public function arquivos()
    {
        return $this->hasMany(Arquivo::class, 'inscricao_id');
    }

    public function chamada()
    {
        return $this->belongsTo(Chamada::class, 'chamada_id');
    }
}
