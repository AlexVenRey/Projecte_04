<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gimcana extends Model
{
    use HasFactory;

    protected $table = 'gimcanas'; // Nombre de la tabla en la base de datos

    protected $fillable = ['nombre', 'descripcion', 'creado_por'];

    /**
     * Relación Many-to-Many con los lugares.
     */
    public function lugares()
    {
        return $this->belongsToMany(Lugar::class, 'gimcana_lugar', 'gimcana_id', 'lugar_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'gimcana_usuario', 'gimcana_id', 'usuario_id');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'gimcana_grupo', 'gimcana_id', 'grupo_id')
            ->withTimestamps();
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
