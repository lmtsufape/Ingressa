<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'candidato_id');
    }
}
