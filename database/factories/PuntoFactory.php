<?php

namespace Database\Factories;

use App\Models\Punto;
use Illuminate\Database\Eloquent\Factories\Factory;

class PuntoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Punto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        $usuario_id = mt_rand(9991336783, 9991336789);
        $lat = mt_rand(19458180,19459780)/1000000;
        $lng = (mt_rand(99115250, 99115650)/1000000)*-1;
        $fecha = date('Y-m-d');
        $hora = date("H:i:s");
        // $fecha = $this->faker->dateTimeBetween($startDate = '-1 days', $endDate = 'now', $timezone = null)->format('Y-m-d');
        // $hora = $this->faker->time('H:i:s', rand(1,44000));
        return [
            //
            "usuario_id" => $usuario_id,
            "lat" => $lat,
            "lng" => $lng,
            "hora" => $hora,
            "fecha" => $fecha
        ];
    }
}
