<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD
=======
use App\Models\Etiqueta;
use App\Models\User as Usuario;
use Illuminate\Support\Facades\Auth;
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539

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

<<<<<<< HEAD
    // Cambiado de 'usuario' a 'creador' para coincidir con el controlador
=======
    /**
     * Relación Many-to-One con el usuario que creó el lugar.
     */
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

<<<<<<< HEAD
=======
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
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539
    public function getIconoAttribute()
    {
        if ($this->etiquetas->isNotEmpty()) {
            return $this->etiquetas->first()->icono;
        }
        return 'fa-map-marker-alt';
    }
<<<<<<< HEAD
}
=======

    public function getEsFavoritoAttribute()
    {
        if (Auth::check()) {
            return $this->usuariosFavoritos()->where('usuario_id', Auth::id())->exists();
        }
        return false;
    }
}
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539
