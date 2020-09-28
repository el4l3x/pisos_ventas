<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Detalle_venta;
use Faker\Generator as Faker;
use App\Inventario;
use App\Venta;

$factory->define(Detalle_venta::class, function (Faker $faker) {
	$subtotal = $faker->numberBetween($min = 150000, $max = 300000);
	$iva = ($subtotal * 12) / 100;


    return [
        //
        'inventario_id' => Inventario::all()->random()->id,
        'venta_id' => Venta::all()->random()->id,
        'cantidad' => $faker->numberBetween($min = 3, $max = 20),
        'sub_total' => $subtotal,
        'iva' => $iva,
        'total' => $subtotal + $iva
    ];
});
