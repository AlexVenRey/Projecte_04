<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'ubicacion_actual'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'ubicacion_actual' => 'array'
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Obtiene los lugares favoritos del usuario.
     */
    public function favoritos(): BelongsToMany
    {
        return $this->belongsToMany(Lugar::class, 'favoritos', 'usuario_id', 'lugar_id')
                    ->withTimestamps();
    }

    /**
     * Obtiene los puntos del usuario.
     */
    public function puntos(): BelongsToMany
    {
        return $this->belongsToMany(Lugar::class, 'puntos_usuarios', 'usuario_id', 'lugar_id')
                    ->withTimestamps();
    }

    // Lugares que ha creado el usuario
    public function lugaresCreados()
    {
        return $this->hasMany(Lugar::class, 'creado_por');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'usuarios_grupos', 'usuario_id', 'grupo_id')
                    ->withPivot('esta_listo')
                    ->withTimestamps();
    }
}
