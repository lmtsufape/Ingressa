<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
