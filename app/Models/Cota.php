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
        'L1' => 'Candidatos com renda familiar bruta per capita igual ou inferior a 1 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L2' => 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L5' => 'Candidatos que, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L6' => 'Candidatos autodeclarados pretos, pardos ou indígenas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L9' => 'Candidatos com deficiência, que tenham renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)',
        'L10' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas, que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)',
        'L13' => 'Candidatos com deficiência, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'L14' => 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'B4342' => 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
        'LB_Q'=> 'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
        'LI_Q'=> 'Candidatos autodeclarados quilombolas, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).',
    ];

    public const COTA_RACIAL = [
        'L2' => true,
        'L6' => true,
        'L10' => true,
        'L14' => true,

        'A0' => false,
        'L1' => false,
        'L5' => false,
        'L9' => false,
        'L13' => false,
        'LB_Q' => false,
        'LI_Q' => false,
    ];

    public const COTA_DEFICIENCIA = [
        'L9' => true,
        'L10' => true,
        'L13' => true,
        'L14' => true,

        'A0' => false,
        'L1' => false,
        'L2' => false,
        'L5' => false,
        'L6' => false,
        'LB_Q' => false,
        'LI_Q' => false,
    ];

    protected $fillable = [
        'nome',
        'descricao',
        'cod_cota',
        'cod_novo'
    ];

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cota_curso', 'cota_id', 'curso_id')->withPivot('id', 'vagas_ocupadas', 'quantidade_vagas', 'sisu_id');
    }

    public function remanejamentos()
    {
        return $this->hasMany(Remanejamento::class, 'cota_id')->orderBy('ordem');
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

    public function analistas()
    {
        return $this->belongsToMany(User::class, 'cota_user', 'cota_id', 'user_id');
    }
}
