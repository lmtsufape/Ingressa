<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listagem extends Model
{
    use HasFactory;

    public const TIPO_ENUM = [
        'convocacao'      => 1,
        'pendencia'       => 2,
        'resultado'       => 3,
    ];

    protected $fillable = [
        'titulo',
        'tipo',
        'caminho_listagem',
    ];

    public function chamada()
    {
        return $this->belongsTo(Chamada::class, 'chamada_id');
    }

    public function setAtributes($input)
    {
        $this->titulo = $input['titulo'];
        $this->tipo = $input['tipo'];
        $this->chamada_id = $input['chamada'];
    }
}
