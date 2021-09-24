<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'caminho_listagem',
    ];

}
