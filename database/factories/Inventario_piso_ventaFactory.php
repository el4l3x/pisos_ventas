<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Inventario_piso_venta;
use Faker\Generator as Faker;
use App\Inventario;
use App\Piso_venta;

$factory->define(Inventario_piso_venta::class, function (Faker $faker) {
    return [
        //
        'inventario_id' => Inventario::all()->random()->id,
    	'piso_venta_id' => Piso_venta::all()->random()->id,
    	'cantidad' => $faker->numberBetween($min = 10, $max = 50),
    ];
});
