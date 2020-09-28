<?php

use Illuminate\Database\Seeder;

class InventariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Inventario::class, 5)->create()->each(function($inventario){

        	$inventario = $inventario->precio()->save(Factory(App\Precio::class)->make());
        });
    }
}
