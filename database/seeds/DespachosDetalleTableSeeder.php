<?php

use Illuminate\Database\Seeder;

class DespachosDetalleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Despacho_detalle::class, 20)->create();
    }
}
