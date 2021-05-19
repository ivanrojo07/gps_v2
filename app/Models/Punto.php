<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Punto extends Model
{
    use HasFactory;

    protected $fillable = [
        'historial_id',
        'usuario_id',
        'lat',
        'lng',
        'hora',
        'fecha',
        'duracion'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function punto_interaccions()
    {
        return $this->hasMany('App\Models\PuntoInteraccion','punto_usuario_id','id');
    }

    public function usuario_360(){
        $response = Http::post(env("CLARO_URL"),[
            "id360" => $this->usuario_id
        ]);
        if ($response->ok()) {
            if ($response->json()["success"]) {
                $array = $response->json();
                // dd($array["icon"]);
                $obj = [
                    "nombre" => $array["nombre"],
                    "apellido_paterno" => $array["apellido_paterno"],
                    "apellido_materno" => $array["apellido_materno"],
                    "icon" => (isset($array["icon"]) ? $array["icon"] : "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png"),
                    "image" => (isset($array["img"]) ? $array["img"] : "/images/user.png"),
                ];
                return $obj;

            }
            return null;
        }
        return $response->body();
    }
}
