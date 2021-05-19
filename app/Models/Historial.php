<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;

    protected $fillable=[
        'usuario_id',
        'fecha'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function puntos(){
        return $this->hasMany('App\Models\Punto','historial_id','id');
    }
}
