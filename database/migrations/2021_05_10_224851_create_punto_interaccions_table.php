<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuntoInteraccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punto_interaccions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('punto_usuario_id');
            $table->bigInteger('punto_interaccion_id');
            $table->bigInteger('interaccion_id');
            $table->decimal('distancia',8,2);
            $table->time('tiempo',0);
            $table->foreign('punto_usuario_id')->references('id')->on('puntos');
            $table->foreign('punto_interaccion_id')->references('id')->on('puntos');
            $table->foreign('interaccion_id')->references('id')->on('interaccions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('punto_interaccions');
    }
}
