<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CotaRequest;

class Cota extends Model
{
    use HasFactory;

    public const COD_COTA_ENUM = [
        'A0' => 'Ampla concorrência',
        'L1' => 'Candidatos com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L2' => 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L5' => 'Candidatos que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L6' => 'Candidatos autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L9' => 'Candidatos com deficiência que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L10' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas, que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)',
        'L13' => 'Candidatos com deficiência que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L14' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'B4342' => 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
    ];

    protected $fillable = [
        'nome',
        'descricao',
        'cod_cota',
    ];

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cota_curso', 'cota_id', 'curso_id')->withPivot('vagas_ocupadas', 'quantidade_vagas');
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

    public function getCodCota()
    {
        return $this::COD_COTA_ENUM[$this->cod_cota];
    }
}
