<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CotaRequest;

class Cota extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'cod_cota',
    ];

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cota_curso', 'cota_id', 'curso_id')->withPivot('vagas_ocupadas', 'percentual_cota');
    }

    public function remanejamentos() 
    {
        return $this->hasMany(Remanejamento::class, 'cota_id');
    }

    public function remanejamento()
    {
        return $this->belongsTo(Remanejamento::class, 'id_prox_cota');
    }

    public function setAtributes(CotaRequest $request)
    {
        $this->nome = $request->nome;
        $this->descricao = $request->input('descrição');
        $this->cod_cota = $request->codigo;
    }
}
