<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CursoRequest;
use Illuminate\Support\Facades\Storage;

class Curso extends Model
{
    use HasFactory;

    public const TURNO_ENUM = [
        'matutino'      => 1,
        'vespertino'    => 2,
        'noturno'       => 3,
        'integral'      => 4,
    ];

    public const GRAU_ENUM = [
        'bacharelado'      => 1,
        'licenciatura'    => 2,
        'tecnologo'       => 3,
    ];

    protected $fillable = [
        'nome',
        'turno',
        'cod_curso',
        'vagas',
        'cor_padrao',
        'icone',
        'grau_academico'
    ];

    public function cotas()
    {
        return $this->belongsToMany(Cota::class, 'cota_curso', 'curso_id', 'cota_id')->withPivot('vagas_ocupadas', 'quantidade_vagas');
    }

    public function setAtributes(CursoRequest $request)
    {
        $this->nome = $request->nome;
        $this->turno = $request->turno;
        $this->cod_curso = $request->codigo;
        $this->vagas = $request->quantidade_de_vagas;
        $this->cor_padrao = $request->cor;
        $this->grau_academico = $request->input('grau_acadêmico');
        if ($this->id != null) {
            $this->update();
        } else {
            $this->save();
        }
        $this->icone = $request->file('icone') != null ? $this->salvarArquivo($request->file('icone')) : null;
    }

    public function salvarArquivo($file)
    {
        if (Storage::disk()->exists('public/'.$this->icone)) {
            Storage::delete('public/'.$this->icone);
        }

        $path = 'curso/' . $this->id . '/';
        $name = $file->getClientOriginalName();
        Storage::putFileAs('public/' . $path, $file, $name);
        $this->icone = $path . $name;
        $this->update();
    }
}
