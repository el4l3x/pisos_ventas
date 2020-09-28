<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Venta;
use Faker\Generator as Faker;
use App\Piso_venta;

$factory->define(Venta::class, function (Faker $faker) {
	$subtotal = $faker->numberBetween($min = 150000, $max = 300000);
	$iva = ($subtotal * 12) / 100;

    return [
        //
        'piso_venta_id' => Piso_venta::all()->random()->id,
        'sub_total' => $subtotal,
        'iva' => $iva,
        'total' => $subtotal + $iva,
        'type' => $faker->randomElement([1, 2])
    ];
});
