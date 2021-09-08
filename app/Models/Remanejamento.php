<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remanejamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'ordem',
        'cota_id',
        'id_prox_cota',
    ];

    public function cota()
    {
        return $this->belongsTo(Cota::class, 'cota_id');
    }

    public function proximaCota()
    {
        return $this->hasOne(Cota::class, 'id_prox_cota');
    }

    public function setAtributes($ordem, Cota $cota, Cota $prox_cota)
    {
        $this->ordem = $ordem;
        $this->cota_id = $cota->id;
        $this->id_prox_cota = $prox_cota->id;
    }
}
