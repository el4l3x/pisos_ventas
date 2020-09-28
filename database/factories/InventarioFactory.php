<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Inventario;
use Faker\Generator as Faker;
use App\Inventory;

$factory->define(Inventario::class, function (Faker $faker) {
	$cantidad = $faker->numberBetween($min = 50, $max = 150);
	$cantidad_por_unidad = $faker->numberBetween($min = 20, $max = 30);

    return [
    	'name' => $faker->word,
    	'total_qty_prod' => $cantidad,
    	'status' => "1",
    	'qty_per_unit' => $cantidad_por_unidad,
    	'unit_type_menor' => "kg",
    	'unit_type_mayor' => 'bulto',
    	'quanty' => $cantidad / $cantidad_por_unidad,
        'inventory_id' => Inventory::all()->random()->id
    ];
});
