<?php

use Illuminate\Database\Seeder;

class Detalle_ventasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Detalle_venta::class, 20)->create();
    }
}
