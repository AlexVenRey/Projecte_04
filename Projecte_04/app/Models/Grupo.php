<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    // Deshabilitar timestamps si tu tabla no los tiene
    public $timestamps = true;

    /**
     * Relación Many-to-Many con los usuarios.
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuarios_grupos', 'grupo_id', 'usuario_id');
    }

    /**
     * Relación Many-to-Many con las gimcanas.
     */
    public function gimcanas()
    {
        return $this->belongsToMany(Gimcana::class, 'gimcana_grupo', 'grupo_id', 'gimcana_id');
    }
}
