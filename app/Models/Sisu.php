<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class Sisu extends Model
{
    use HasFactory;

    protected $fillable = [
        'edicao',
    ];

    public function setAtributes($input)
    {
        $this->edicao = $input['edicao'];
    }

    public function chamadas()
    {
        return $this->hasMany(Chamada::class, 'sisu_id');
    }

    public function salvar_import_regular($file) 
    {
        if($this->caminho_import_regular != null) {
            if (Storage::disk()->exists('public/' . $this->caminho_import_regular)) {
                Storage::delete('public/' . $this->caminho_import_regular);
            }
        }

        $path = 'sisu/'.$this->id.'/';
        $nomeRegular = $file->getClientOriginalName();
        Storage::putFileAs('public/'.$path, $file, $nomeRegular);
        $this->caminho_import_regular = $path . $nomeRegular;
        $this->update();
    }

    public function salvar_import_espera($file) 
    {
        if($this->caminho_import_espera != null){
            if (Storage::disk()->exists('public/' . $this->caminho_import_espera)) {
                Storage::delete('public/' . $this->caminho_import_espera);
            }
        }

        $path = 'sisu/'.$this->id.'/';
        $nomeEspera = $file->getClientOriginalName();
        Storage::putFileAs('public/'.$path, $file, $nomeEspera);
        $this->caminho_import_espera = $path . $nomeEspera;
        $this->update();
    }
}
