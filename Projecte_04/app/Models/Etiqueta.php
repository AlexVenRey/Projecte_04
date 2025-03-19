<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';
    
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function lugares(): BelongsToMany
    {
        return $this->belongsToMany(Lugar::class, 'lugar_etiqueta');
    }
}
