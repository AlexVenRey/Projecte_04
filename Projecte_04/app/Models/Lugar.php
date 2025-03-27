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

    /**
     * Relación Many-to-Many con las etiquetas.
     */
    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'lugar_etiqueta');
    }

    /**
     * Relación Many-to-One con el usuario que creó el lugar.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /**
     * Relación Many-to-Many con los usuarios que lo tienen como favorito.
     */
    public function usuariosFavoritos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoritos', 'lugar_id', 'usuario_id')
                    ->withTimestamps();
    }

    /**
     * Relación Many-to-Many con los usuarios que han añadido el punto.
     */
    public function usuariosPuntos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'puntos_usuarios', 'lugar_id', 'usuario_id')
                    ->withTimestamps();
    }

    /**
     * Obtiene el icono del lugar.
     */
    public function getIconoAttribute()
    {
        // Si el lugar tiene etiquetas, devolver el icono de la primera etiqueta
        if ($this->etiquetas->isNotEmpty()) {
            return $this->etiquetas->first()->icono;
        }
        
        // Si no tiene etiquetas, devolver un icono por defecto
        return 'fa-map-marker-alt';
    }

    public function getEsFavoritoAttribute()
    {
        if (Auth::check()) {
            return $this->usuariosFavoritos()->where('usuario_id', Auth::id())->exists();
        }
        return false;
    }
}
