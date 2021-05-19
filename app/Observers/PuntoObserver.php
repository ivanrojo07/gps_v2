<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Punto;
use App\Models\Historial;
use App\Models\Interaccion;
use App\Jobs\SetInteraccion;
use App\Models\PuntoInteraccion;

class PuntoObserver
{
    /**
     * Handle the Punto "created" event.
     *
     * @param  \App\Models\Punto  $punto
     * @return void
     */
    public function created(Punto $punto)
    {
        //
        // Starting clock time in seconds
        // $start_time = microtime(true);
        $last_punto = Punto::where('usuario_id',$punto->usuario_id)
                        ->where('fecha',$punto->fecha)->where('id',"<",$punto->id)->orderBy('hora','DESC')->first();
        
        if($last_punto){
            $diff = Carbon::parse($punto->hora)->diffInSeconds($last_punto->hora);
            $last_punto->duracion = gmdate('H:i:s', $diff);
            $last_punto->save();
        }

        SetInteraccion::dispatch($punto);
        // // End clock time in seconds
        // $end_time = microtime(true);
        
        // // Calculate script execution time
        // $execution_time = ($end_time - $start_time);
        // var_dump($execution_time." segundos");
    }
    /**
     * Handle the Punto "updated" event.
     *
     * @param  \App\Models\Punto  $punto
     * @return void
     */
    public function updated(Punto $punto)
    {
        //
    }

    /**
     * Handle the Punto "deleted" event.
     *
     * @param  \App\Models\Punto  $punto
     * @return void
     */
    public function deleted(Punto $punto)
    {
        //
    }

    /**
     * Handle the Punto "restored" event.
     *
     * @param  \App\Models\Punto  $punto
     * @return void
     */
    public function restored(Punto $punto)
    {
        //
    }

    /**
     * Handle the Punto "force deleted" event.
     *
     * @param  \App\Models\Punto  $punto
     * @return void
     */
    public function forceDeleted(Punto $punto)
    {
        //
    }
}
