<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Despacho_detalle;
use Faker\Generator as Faker;
use App\Despacho;
use App\Inventory;

$factory->define(Despacho_detalle::class, function (Faker $faker) {
    return [
        //
        'despacho_id' => Despacho::all()->random()->id,
        'inventory_id' => Inventory::all()->random()->id,
        'cantidad' => $faker->numberBetween($min = 3, $max = 20)
    ];
});
