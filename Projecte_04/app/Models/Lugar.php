<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    use HasFactory;

    protected $table = 'lugares';

    protected $fillable = ['nombre', 'descripcion', 'direccion', 'latitud', 'longitud', 'icono', 'color_marcador', 'creado_por'];

    /**
     * Relación Many-to-Many con las etiquetas.
     */
    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'lugares_etiquetas', 'lugar_id', 'etiqueta_id');
    }

    /**
     * Relación Many-to-Many con las gimcanas.
     */
    public function gimcanas()
    {
        return $this->belongsToMany(Gimcana::class, 'gimcana_lugar', 'lugar_id', 'gimcana_id');
    }
}
