<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Punto;
use App\Models\Interaccion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePuntoRequest;
use App\Http\Requests\SearchInteraccionRequest;

class PuntoController extends Controller
{
    //

    public function store(StorePuntoRequest $request)
    {
        $punto = Punto::create($request->all());
        return response()->json(['punto'=>$punto],201);
    }

    public function searchInteraccion(SearchInteraccionRequest $request){

        $usuario_id = $request->usuario_id;
        $fecha = $request->fecha;
        $dias = $request->dias ? $request->dias : "15";
        $fecha_fin = Carbon::parse($fecha)->subDays($dias)->toDateString();
        $distancia = $request->distancia;
        $tiempo = $request->tiempo;
        $interaccions = Interaccion::where('usuario_id',$usuario_id)
            ->whereDate('fecha','>=',$fecha_fin)->whereDate('fecha',"<=",$fecha)
            ->with('punto_interaccions',function($q)use($distancia,$tiempo){
                $q->where('distancia',"<=",$distancia)->where('tiempo','<=',$tiempo)->with(['punto_usuario','punto_interaccion']);
            })->get();
        $filter_interaccions = $interaccions->append(['info_usuario360','info_interaccion360'])->filter(function ($interaccion, $key) {
            //var_dump($interaccion->punto_interaccions->isNotEmpty());
            return $interaccion->punto_interaccions->isNotEmpty();
        });
        foreach($filter_interaccions as $interaccion){
            $sum_seconds =0;
            foreach($interaccion->punto_interaccions as $punto){
                $sum_seconds += strtotime($punto->punto_usuario->duracion);
            }
            $interaccion->duracion = date('H:i:s',$sum_seconds);
        }
        
        
        return response()->json($filter_interaccions->all(), 200);

    }
    
    public function prueba()
    {
        # code...
        $interaccion = Interaccion::first();
        dd($interaccion->punto_interaccions()->first());

    }
}
