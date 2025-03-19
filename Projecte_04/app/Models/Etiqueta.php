<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function lugares()
    {
        return $this->belongsToMany(Lugar::class, 'lugares_etiquetas', 'etiqueta_id', 'lugar_id');
    }
}