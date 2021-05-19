<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        for ($i=0; $i <= 1000; $i++) {
            usleep(3000000);
            \App\Models\Punto::factory(5)->create();
        }
    }
}
