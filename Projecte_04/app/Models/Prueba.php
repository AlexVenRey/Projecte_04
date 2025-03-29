<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prueba extends Model
{
    protected $table = 'pruebas';
    
    protected $fillable = [
        'punto_control_id',
        'descripcion',
        'respuesta'
    ];

    public function puntoControl()
    {
        return $this->belongsTo(PuntoControl::class);
    }

    public function lugar(): BelongsTo
    {
        return $this->belongsTo(Lugar::class);
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'grupo_prueba')
                    ->withPivot('completada')
                    ->withTimestamps();
    }
}
