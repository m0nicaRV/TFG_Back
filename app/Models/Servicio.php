<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    /**
     * Relación de muchos a muchos con User a través de la tabla citas.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'citas', 'servicio_id', 'user_id')
                    ->withPivot('descripcion', 'dias_disponibles', 'estado', 'fecha');
    }
}
