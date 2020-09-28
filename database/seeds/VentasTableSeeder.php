<?php

use Illuminate\Database\Seeder;

class VentasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Venta::class, 5)->create()->each(function($despacho){

            $despacho->id_extra = $despacho->id;
            $despacho->save();
        });
    }
}
