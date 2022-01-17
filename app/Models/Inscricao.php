<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    use HasFactory;

    public const STATUS_ENUM = [
        'documentos_pendentes' => 1,
        'documentos_enviados' => 2,
        'documentos_aceitos_sem_pendencias' => 3,
        'documentos_aceitos_com_pendencias' => 4,
        'documentos_invalidados' => 5,
    ];

    public const STATUS_VALIDACAO_CANDIDATO = [
        'cadastro_validado' => 1,
        'cadastro_invalidado_confirmacao' => 2,
        'cadastro_invalidado' => 3,
    ];

    protected $fillable = [
        'candidato_id',
        'chamada_id',
        'cota_id',
        'curso_id',
        'protocolo',
        'status',
        'cd_efetivado',
        'justificativa',
        'nu_etapa',
        'no_campus',
        'co_ies_curso',
        'no_curso',
        'ds_turno',
        'ds_formacao',
        'qt_vagas_concorrencia',
        'co_inscricao_enem',
        'no_inscrito',
        'nu_cpf_inscrito',
        'dt_nascimento',
        'tp_sexo',
        'nu_rg',
        'no_mae',
        'ds_logradouro',
        'nu_endereco',
        'ds_complemento',
        'sg_uf_inscrito',
        'no_municipio',
        'no_bairro',
        'nu_cep',
        'nu_fone1',
        'nu_fone2',
        'ds_email',
        'nu_nota_l',
        'nu_nota_ch',
        'nu_nota_cn',
        'nu_nota_m',
        'nu_nota_r',
        'co_curso_inscricao',
        'st_opcao',
        'no_modalidade_concorrencia',
        'st_bonus_perc',
        'qt_bonus_perc',
        'no_acao_afirmativa_bonus',
        'nu_nota_candidato',
        'nu_notacorte_concorrida',
        'nu_classificacao',
        'ds_matricula',
        'dt_operacao',
        'co_ies',
        'no_ies',
        'sg_ies',
        'sg_uf_ies',
        'st_lei_optante',
        'st_lei_renda',
        'st_lei_etnia_p',
        'st_lei_etnia_i',
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

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function cota()
    {
        return $this->belongsTo(Cota::class, 'cota_id');
    }

    public function cotaRemanejada()
    {
        return $this->belongsTo(Cota::class, 'cota_vaga_ocupada_id');
    }
 
    public function sisu()
    {
        return $this->belongsTo(Sisu::class, 'sisu_id');
    }

    public function notaMedia()
    {
        return ($this->nu_nota_l + $this->nu_nota_ch + $this->nu_nota_cn + $this->nu_nota_m + $this->nu_nota_r) / 5;
    }

    public function isArquivoEnviado($nome)
    {
        return $this->arquivo($nome) != null;
    }

    public function isArquivoNaoEnviado($nome)
    {
        return $this->arquivo($nome) == null;
    }

    public function isArquivoAvaliado($nome)
    {
        if ($this->isArquivoEnviado($nome)) {
            return $this->arquivo($nome)->avaliacao != null;
        }
        return false;
    }

    public function isArquivoRecusado($nome)
    {
        if ($this->isArquivoAvaliado($nome)) {
            return $this->arquivo($nome)->avaliacao->isRecusado();
        }
        return false;
    }

    public function arquivo($nome)
    {
        return $this->arquivos()->where('nome', $nome)->first();
    }

    public function isDocumentosRequeridos()
    {
        return $this->status == self::STATUS_ENUM['documentos_pendentes'];
    }

    public function isDocumentosInvalidados()
    {
        return $this->status == self::STATUS_ENUM['documentos_invalidados'];
    }
}
