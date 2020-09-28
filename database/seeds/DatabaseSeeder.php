<?php

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
        $this->call(InventoriesTableSeeder::class);
    	$this->call(UsersTableSeeder::class);/*
    	$this->call(InventariosTableSeeder::class);
        
        $this->call(Inventario_piso_ventasTableSeeder::class);
        $this->call(VentasTableSeeder::class);
        $this->call(Detalle_ventasTableSeeder::class);
        $this->call(DespachosTableSeeder::class);
        $this->call(DespachosDetalleTableSeeder::class);*/
    }
}
