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

    public const DOC_NOME = [
        'certificado_conclusao' => "Certificado de Conclusão do Ensino Médio",
        'historico' => "Histórico Escolar do Ensino Médio ou equivalente",
        'nascimento_ou_casamento' => "Registro de Nascimento ou Certidão de Casamento",
        'cpf' => "Cadastro de Pessoa Física (CPF)",
        'rg' => "Carteira de Identidade (RG)",
        'quitacao_eleitoral' => "Comprovante de quitação com o Serviço Eleitoral",
        'quitacao_militar' => "Comprovante de quitação com o Serviço Militar",
        'foto' => "Foto 3x4",
        'autodeclaracao' => "Autodeclaração de cor/etnia",
        'comprovante_renda' => "Comprovante de renda",
        'laudo_medico' => "Laudo médico e exames",
        'declaracao_veracidade' => "Declaração de Veracidade",
        'rani' => "Declaração Indígena",
        'heteroidentificacao' => "Vídeo de Heteroidentificação",
        'fotografia' => "Foto de Heteroidentificação",
        'declaracao_cotista' => "Declaração Cotista",
        'declaracao_quilombola' => "Declaração da Fundação Cultural Palmares ou Declaração de pertencimento Ético e de Vínculo com Comunidade Quilombola assinada por 03 (três) lideranças da Comunidade",
    ];

    public function inscricao()
    {
        return $this->belongsTo(Inscricao::class, 'inscricao_id');
    }

    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class, 'arquivo_id');
    }

    public function getNomeDoc()
    {
        return $this::DOC_NOME[$this->nome];
    }
}
