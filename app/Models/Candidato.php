<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nu_cpf_inscrito',
        'dt_nascimento',
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
}
