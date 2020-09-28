<?php

use Illuminate\Database\Seeder;

class DespachosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Despacho::class, 7)->create()->each(function($despacho){

            $despacho->id_extra = $despacho->id;
            $despacho->save();
        });
    }
}
