<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamada extends Model
{
    use HasFactory;

    protected $fillable = [
        'sisu_id',
        'nome',
        'descricao',
        'regular',
        'caminho_import_sisu_gestao',
        'job_batch_id',
        'confirmacao',
    ];

    public function setAtributes($input)
    {
        $this->nome = $input['nome'];
        $this->descricao = $input['descricao'];
    }

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'chamada_id');
    }

    public function sisu()
    {
        return $this->belongsTo(Sisu::class, 'sisu_id');
    }

    public function datasChamada()
    {
        return $this->hasMany(DataChamada::class, 'chamada_id')->orderBy('tipo');
    }

    public function listagem()
    {
        return $this->hasMany(Listagem::class, 'chamada_id')->orderBy('created_at', 'DESC');
    }

    public function listagemAnteriores()
    {
        return $this->hasMany(Listagem::class, 'chamada_id')->orderBy('created_at');
    }

    /**
     * Retorna o nome da chamada respeitando a posição e se é regular ou não.
     *
     * @return string $string
     */
    public function getNomeRegular() 
    {
        $chamadas = $this->sisu->chamadas;
        $chamadas = $chamadas->sortBy('created_at');

        foreach ($chamadas as $posicao => $chamada) {
            if ($this->id == $chamadas[$posicao]->id) {
                if ($posicao == 0) {
                    return 'chamada regular';
                } else {
                    return $posicao . 'ª chamada de lista de espera';
                }
            }
        }        
    }

    /**
     * Retorna o nome da chamada respeitando a posição e se é regular ou não, supondo
     * que a listagem seja de pendecias.
     *
     * @return string $string
     */
    public function getNomeRegularPendecia() 
    {
        $chamadas = $this->sisu->chamadas;
        $chamadas = $chamadas->sortBy('created_at');

        foreach ($chamadas as $posicao => $chamada) {
            if ($this->id == $chamadas[$posicao]->id) {
                if ($posicao == 0) {
                    return 'retificação de documentos da chamada regular';
                } else {
                    return 'retificação de documentos da ' . $posicao . 'ª chamada de lista de espera';
                }
            }
        }  
    }
}
