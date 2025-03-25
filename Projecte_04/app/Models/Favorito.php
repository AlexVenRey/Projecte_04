<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    protected $table = 'favoritos';
    
    protected $fillable = [
        'usuario_id',
        'lugar_id'
    ];

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
