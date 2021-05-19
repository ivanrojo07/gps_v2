<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoInteraccion extends Model
{
    use HasFactory;


    protected $fillable=[
        'punto_usuario_id',
        'punto_interaccion_id',
        'interaccion_id',
        'distancia',
        'tiempo'
    ];

    public function punto_usuario()
    {
        return $this->hasOne('App\Models\Punto','id','punto_usuario_id');
    }

    public function punto_interaccion()
    {
        return $this->hasOne('App\Models\Punto','id','punto_interaccion_id');
    }

    public function interaccion()
    {
        return $this->belongsTo('App\Models\Interaccion');
    }
}
