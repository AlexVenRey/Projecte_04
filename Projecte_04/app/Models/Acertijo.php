<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acertijo extends Model
{
    protected $fillable = [
        'gimcana_id',
        'lugar_id',
        'texto_acertijo',
        'pista',
        'latitud_acertijo',
        'longitud_acertijo',
        'orden'
    ];

    public function gimcana()
    {
        return $this->belongsTo(Gimcana::class);
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    public function progresos()
    {
        return $this->hasMany(ProgresoGimcana::class, 'acertijo_actual_id');
    }
}
