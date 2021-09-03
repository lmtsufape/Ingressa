<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CursoRequest;

class Curso extends Model
{
    use HasFactory;

    public const TURNO_ENUM = [
        'matutino'      => 1,
        'vespertino'    => 2,
        'noturno'       => 3,
        'integral'      => 4,
    ];
    protected $fillable = [
        'nome',
        'turno',
        'cod_curso',
        'vagas',
    ];

    public function cotas()
    {
        return $this->belongsToMany(Curso::class, 'cota_curso', 'curso_id', 'cota_id')->withPivot('vagas_ocupadas', 'percentual_cota');
    }

    public function setAtributes(CursoRequest $request) 
    {
        $this->nome = $request->nome;
        $this->turno = $request->turno;
        $this->cod_curso = $request->codigo;
        $this->vagas = $request->quantidade_de_vagas;
    }
}
