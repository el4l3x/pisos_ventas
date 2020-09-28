<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Despacho;
use Faker\Generator as Faker;
use App\Piso_venta;

$factory->define(Despacho::class, function (Faker $faker) {
    return [
        //
        'piso_venta_id' => Piso_venta::all()->random()->id,
        'type' => $faker->randomElement([1, 2]),
        'confirmado' => $faker->randomElement([true, false]),
    ];
});
