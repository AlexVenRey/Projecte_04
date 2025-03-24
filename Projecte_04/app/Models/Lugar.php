<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lugar extends Model
{
    use HasFactory;

    protected $table = 'lugares';

    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'descripcion',
        'icono',
        'color_marcador',
        'creado_por'
    ];

    protected $appends = ['es_favorito'];

    /**
     * Relación Many-to-Many con las etiquetas.
     */
    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'lugar_etiqueta');
    }

    /**
     * Relación One-to-Many con los favoritos.
     */
    public function favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class);
    }

    /**
     * Obtiene si el lugar es favorito del usuario autenticado.
     */
    public function getEsFavoritoAttribute(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->favoritos()->where('usuario_id', auth()->id())->exists();
    }
}
