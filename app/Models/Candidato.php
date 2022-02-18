<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    public const COR_RACA = [
        1 => 'Branco(a)',
        2 => 'Preto(a)',
        3 => 'Pardo(a)',
        4 => 'Amarelo(a)',
        5 => 'Indígena',
    ];

    public const NECESSIDADES = [
        'nenhuma'=>'Nenhuma',
        2017=>'Altas habilidades/Superdotação',
        2013=>'Autismo infantil',
        2008=>'Deficiência auditiva',
        2009=>'Deficiência física',
        2012=>'Deficiência intelectual',
        2011=>'Deficiência múltipla',
        2001=>'Surdez',
        2004=>'Cegueira',
        2005=>'Visão sub-normal ou baixa visão',
        2014=>'Síndrome de Asperger',
        2015=>'Síndrome de Rett',
        2010=>'Surdocegueira',
        2016=>'Transtorno Desintegrativo da Infância',
    ];

    public const ETNIA = [
        'indigena' => 'Indígena',
        'quilombola' => 'Quilombola',
        'outros' => 'Outros',
    ];

    public const ESTADO_CIVIL = [
        1 => 'Solteiro(a)',
        2 => 'Casado(a)',
        3 => 'Separado(a) judicialmente',
        4 => 'Divorciado(a)',
        5 => 'Viuvo(a)',
    ];

    protected $fillable = [
        'user_id',
        'no_inscrito',
        'no_social',
        'nu_cpf_inscrito',
        'dt_nascimento',
        'orgao_expedidor',
        'uf_rg',
        'data_expedicao',
        'titulo',
        'zona_eleitoral',
        'secao_eleitoral',
        'cidade_natal',
        'reside',
        'uf_natural',
        'pais_natural',
        'estado_civil',
        'pai',
        'localidade',
        'escola_ens_med',
        'uf_escola',
        'ano_conclusao',
        'modalidade',
        'concluiu_publica',
        'necessidades',
        'cor_raca',
        'etnia',
        'trabalha',
        'grupo_familiar',
        'valor_renda',
        'atualizar_dados',
    ];

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'candidato_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCpfPDF()
    {
        $cpf = "";
        for ($i = 0; $i < strlen($this->nu_cpf_inscrito); $i++) {
            if ($i > 2 && $i < 7) {
                $cpf .= "*";
            } else {
                $cpf .= $this->nu_cpf_inscrito[$i];
            }

        }
        return $cpf;
    }

    public function isPretoOrPardo()
    {
        return $this->cor_raca == 2 || $this->cor_raca == 3;
    }
}
