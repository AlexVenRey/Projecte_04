<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gimkana extends Model
{
    use HasFactory;

    // Especifica los campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        // Añade otros campos según tu estructura de base de datos
    ];

    // Si tienes relaciones, defínelas aquí
    public function puntosControl()
    {
        return $this->hasMany(PuntoControl::class);
    }
}

class PuntoControl extends Model
{
    public function pruebas()
    {
        return $this->hasMany(Prueba::class);
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'puntos_control_grupos');
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'lugar_id');
    }
}

class Prueba extends Model
{
    public function puntoControl()
    {
        return $this->belongsTo(PuntoControl::class);
    }
}

class Ruta extends Model
{
    protected $casts = [
        'origen' => 'array',
        'destino' => 'array',
    ];
}
