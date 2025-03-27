<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Etiqueta;
use App\Models\User as Usuario;
use Illuminate\Support\Facades\Auth;

class Lugar extends Model
{
    use HasFactory;

    protected $table = 'lugares';

    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'descripcion',
        'color_marcador',
        'creado_por'
    ];

    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'lugar_etiqueta');
    }

    // Cambiado de 'usuario' a 'creador' para coincidir con el controlador
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function getIconoAttribute()
    {
        if ($this->etiquetas->isNotEmpty()) {
            return $this->etiquetas->first()->icono;
        }
        return 'fa-map-marker-alt';
    }
}