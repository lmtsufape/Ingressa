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
        return $this->hasMany(DataChamada::class, 'chamada_id');
    }

    public function listagem()
    {
        return $this->hasMany(Listagem::class, 'chamada_id');
    }
}
