<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    use HasFactory;
    protected $table = 'lugares_etiquetas';
    protected $fillable = ['nombre', 'descripcion', 'direccion', 'coordenadas', 'icono', 'color_marcador', 'creado_por'];

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'lugares_etiquetas', 'lugar_id', 'etiqueta_id');
    }
}