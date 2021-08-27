<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'inscricao_id',
        'caminho',
        'nome',
    ];

    public function inscricao()
    {
        return $this->belongsTo(Inscricao::class, 'inscricao_id');
    }

    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class, 'arquivo_id');
    }
}
