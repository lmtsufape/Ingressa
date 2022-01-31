<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    public const AVALIACAO_ENUM = [
        'aceito' => 1,
        'recusado' => 2,
        'reenviado' => 3,
    ];

    protected $fillable = [
        'arquivo_id',
        'user_avaliador_id',
        'avaliacao',
        'comentario',
    ];

    public function arquivo()
    {
        return $this->belongsTo(Arquivo::class, 'arquivo_id');
    }

    public function isRecusado()
    {
        return $this->avaliacao == self::AVALIACAO_ENUM['recusado'];
    }

    public function isAceito()
    {
        return $this->avaliacao == self::AVALIACAO_ENUM['aceito'];
    }

    public function isReenviado()
    {
        return $this->avaliacao == self::AVALIACAO_ENUM['reenviado'];
    }
}
