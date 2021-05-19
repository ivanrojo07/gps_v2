<?php

namespace App\Models;

use App\Models\Punto;
use App\Models\PuntoInteraccion;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interaccion extends Model
{
    use HasFactory;

    protected $fillable=[
        'usuario_id',
        'interaccion_id',
        'fecha'
    ];

//    protected $appends = ['info_usuario360'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function punto_interaccions()
    {
        return $this->hasMany('App\Models\PuntoInteraccion','interaccion_id','id');
    }

    public function getTiempoAttribute()
    {

        $primer_punto = $this->punto_interaccions->first();
        $ultimo_punto = $this->punto_interaccions->last();
        $hora_1 = Carbon::create($primer_punto->punto_usuario->hora);
        $hora_2 = Carbon::create($ultimo_punto->punto_usuario->hora);
        if ($hora_1 >= $hora_2) {
            $tiempo = $hora_1->diffInSeconds($hora_2)+1;
            
        }
        else{
            $tiempo = $hora_2->diffInSeconds($hora_1)+1;
        }
        return date("H:i:s",$tiempo);
         

    }

    public function getInfoUsuario360Attribute(){
        $response = Http::post(env("CLARO_URL"),[
            "id360" => $this->usuario_id
        ]);
        if ($response->ok()) {
            if ($response->json()["success"]) {
                $array = $response->json();
                // dd($array["icon"]);
                $obj = [
                    "nombre" => ($array["nombre"] ? $array["nombre"] : ""),
                    "apellido_paterno" => ($array["apellido_paterno"] ? $array["apellido_paterno"] : ""),
                    "apellido_materno" => ($array["apellido_materno"] ? $array["apellido_materno"] : ""),
                    "icon" => (isset($array["icon"]) ? $array["icon"] : null),
                    "image" => (isset($array["img"]) ? $array["img"] : null),
                ];
                return $obj;

            }
            return [];
        }
        return [];
    }

    public function getInfoInteraccion360Attribute(){
        $response = Http::post(env("CLARO_URL"),[
            "id360" => $this->interaccion_id
        ]);
        // dd($response->json()['success']);
        if ($response->ok()) {
            if ($response->json()["success"]) {
                $array = $response->json();
                // dd($array["icon"]);
                $obj = [
                    "nombre" => ($array["nombre"] ? $array["nombre"] : ""),
                    "apellido_paterno" => ($array["apellido_paterno"] ? $array["apellido_paterno"] : ""),
                    "apellido_materno" => ($array["apellido_materno"] ? $array["apellido_materno"] : ""),
                    "icon" => (isset($array["icon"]) ? $array["icon"] : null),
                    "image" => (isset($array["img"]) ? $array["img"] : null),
                ];
                return $obj;

            }
            return [];
        }
        return [];
    }
}
