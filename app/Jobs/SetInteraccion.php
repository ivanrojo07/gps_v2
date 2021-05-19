<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Punto;
use App\Models\Historial;
use App\Models\Interaccion;
use Illuminate\Bus\Queueable;
use App\Models\PuntoInteraccion;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SetInteraccion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $punto;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Punto $punto)
    {
        $this->punto = $punto;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $historial = Historial::firstOrCreate([
            "usuario_id" => $this->punto->usuario_id,
            "fecha" => $this->punto->fecha
        ]);
        $this->punto->historial_id = $historial->id;
        $this->punto->save();
        $hora_inicio = $this->punto->hora;
        $hora_fin = Carbon::parse($this->punto->hora)->subHours(1)->toTimeString();
        $fecha = $this->punto->fecha;
        $puntos = Punto::whereDate("fecha",$fecha)->where('usuario_id',"!=",$this->punto->usuario_id)->whereBetween('hora',[$hora_fin,$hora_inicio])
                        ->get();
        
        $radio = 6371000;
        $r_lat0 = deg2rad($this->punto->lat);
        $r_lng0 = deg2rad($this->punto->lng);

        foreach ($puntos as $p){
            $r_lat = deg2rad($p->lat);
            $r_lng = deg2rad($p->lng);

            $lonDelta = $r_lng - $r_lng0;

            $distancia = ($radio *
                    acos(
                        cos($r_lat0) * cos($r_lat) * cos($lonDelta) +
                        sin($r_lat0) * sin($r_lat)
                    )
                );

            if($distancia <= 5.0){
                $hora_1 = Carbon::create($this->punto->hora);
                $hora_2 = Carbon::create($p->hora);
                $tiempo = $hora_1->diffInSeconds($hora_2);
                $interaccion = Interaccion::firstOrCreate([
                    "usuario_id" => $this->punto->usuario_id,
                    "interaccion_id" => $p->usuario_id,
                    "fecha" => $this->punto->fecha
                ]);
                $punto_interaccion = PuntoInteraccion::create([
                    "distancia" => $distancia,
                    "punto_usuario_id" => $this->punto->id,
                    "punto_interaccion_id" => $p->id,
                    "interaccion_id" => $interaccion->id,
                    "tiempo" => date("H:i:s",$tiempo)
                ]);
            }

        }
        
    }
}
