<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Precio;
use Faker\Generator as Faker;

$factory->define(Precio::class, function (Faker $faker) {
	$costo = $faker->numberBetween($min = 150000, $max = 300000);
	$subtotal_menor = ((12 * $costo) / 100) + $costo;//con margen de ganancia
	$subtotal_mayor = ((20 * $costo) / 100) + $costo;//con margen de ganancia

	$iva_menor = (12 * $subtotal_menor) / 100;
	$iva_mayor = (12 * $subtotal_mayor) / 100;

	$total_menor = $subtotal_menor + $iva_menor;
	$total_mayor = $subtotal_mayor + $iva_mayor;

    return [
    	'costo' => $costo,
    	'iva_porc_menor' => 12,
    	'iva_menor' => $iva_menor,
    	'sub_total_menor' => $subtotal_menor,
    	'total_menor' => $total_menor,
    	'iva_porc_mayor' => 12,
    	'iva_mayor' => $iva_mayor,
    	'sub_total_mayor' => $subtotal_mayor,
    	'total_mayor' => $total_mayor
    ];
});
