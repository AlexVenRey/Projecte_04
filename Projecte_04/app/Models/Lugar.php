<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lugar extends Model
{
    protected $table = 'lugares';
    
    protected $fillable = [
        'nombre',
        'direccion',
        'latitud',
        'longitud',
        'descripcion',
        'icono',
        'color'
    ];

    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float'
    ];

    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'lugar_etiqueta');
    }

    public function pruebas()
    {
        return $this->hasMany(Prueba::class);
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoritos');
    }
}
