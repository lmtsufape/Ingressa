<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listagem extends Model
{
    use HasFactory;

    public const TIPO_ENUM = [
        'convocacao'      => 1,
        'pendencia'       => 2,
        'resultado'       => 3,
        'final'    => 4,
    ];

    protected $fillable = [
        'titulo',
        'tipo',
        'caminho_listagem',
    ];

    public function chamada()
    {
        return $this->belongsTo(Chamada::class, 'chamada_id');
    }

    public function setAtributes($input)
    {
        $this->titulo = $input['titulo'];
        $this->tipo = $input['tipo'];
        $this->chamada_id = $input['chamada'];
    }

    
    /**
     * Retorna se para esse tipo de listagem deve ser enviado emails.
     * 
     * @return boolean
     */
    public function enviaEmails() 
    {
        return ($this->tipo == $this::TIPO_ENUM['convocacao']) || ($this->tipo == $this::TIPO_ENUM['pendencia']);
    }

    /**
     * Retorna o texto do email da listagem.
     * 
     * @return string
     */
    public function getNomeEvento() 
    {
        switch ($this->tipo) {
            case $this::TIPO_ENUM['convocacao']:
                return $this->chamada->getNomeRegular();
                break;
            case $this::TIPO_ENUM['pendencia']:
                return $this->chamada->getNomeRegularPendecia();
                break;
        }
        
    }
}
