<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grupo;

class PuntoControl extends Model
{
    use HasFactory;

    public function pruebas()
    {
        return $this->hasMany(Prueba::class);
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'control_grupos');
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class, 'lugar_id');
    }
}
