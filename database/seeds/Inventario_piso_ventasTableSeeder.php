<?php

use Illuminate\Database\Seeder;
use App\Inventario_piso_venta;

class Inventario_piso_ventasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Inventario_piso_venta::class, 10)->create();
    }
}
