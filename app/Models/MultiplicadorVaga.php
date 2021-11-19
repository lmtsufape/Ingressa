<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiplicadorVaga extends Model
{
    use HasFactory;

    protected $fillable = [
        'chamada_id',
        'cota_curso_id',
        'multiplicador',
    ];

}
