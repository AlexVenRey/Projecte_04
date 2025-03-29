<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntoControl extends Model
{
    protected $table = 'puntos_control';

    protected $fillable = [
        'lugar_id',
        'pista'
    ];

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    public function prueba()
    {
        return $this->hasOne(Prueba::class);
    }
} 