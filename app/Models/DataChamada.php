<?php

namespace App\Models;

use App\Http\Requests\DataChamadaRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataChamada extends Model
{
    use HasFactory;

    public const TIPO_ENUM = [
        'convocacao'      => 1,
        'envio'    => 2,
        'resultado'    => 3,
    ];

    protected $fillable = [
        'titulo',
        'tipo',
        'data_inicio',
        'data_fim',
    ];

    public function chamada()
    {
        return $this->belongsTo(Chamada::class, 'chamada_id');
    }

    public function setAtributes(DataChamadaRequest $request)
    {
        $this->titulo = $request->titulo;
        $this->tipo = $request->tipo;
        $this->data_inicio = $request->data_inicio;
        $this->data_fim = $request->data_fim;
    }
}
