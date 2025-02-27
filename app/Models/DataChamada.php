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
        'analise' => 3,
        'resultado_parcial' => 4,
        'reenvio' => 5,
        'analise_reenvio' =>6,
        'resultado_final'    => 7,
    ];

    protected $fillable = [
        'titulo',
        'tipo',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
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

    /**
     * Retorna o nome do evento a qual a data pertence
     *
     * Envio de Documentos da Chamada Regular 
     * Retificação de Documentos da Chamada Regular 
     * Envio de Documentos da 1ª Chamada da Lista de Espera 
     * Retificação de Documentos da 1ª Chamada da Lista de Espera   
     * Envio de Documentos da 2ª Chamada da Lista de Espera  
     * Retificação de Documentos da 2ª Chamada da Lista de Espera
     * ... 
     * Envio de Documentos da nª Chamada da Lista de Espera  
     * Retificação de Documentos da nª Chamada da Lista de Espera
     * 
     * @return string $nomeEvento
     */
    public function getNomeEvento()
    {
        switch ($this->tipo) {
            case $this::TIPO_ENUM['envio']:
                return 'envio de documentos da ' . $this->chamada->getNomeRegular();
                break;
            case $this::TIPO_ENUM['reenvio']:
                return 'retificação de documentos da ' . $this->chamada->getNomeRegular();
                break;
        }
    }

    /**
     * Retorna um boleano que indica se a é uma data que emails devem ser enviados
     * 
     * @return boolean
     */
    public function ehDataDeEnvio() 
    {
        switch ($this->tipo) {
            case $this::TIPO_ENUM['envio']:
                return true;
                break;
            case $this::TIPO_ENUM['reenvio']:
                return true;
                break;
        }

        return false;
    }
}
