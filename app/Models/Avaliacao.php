<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

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
}
