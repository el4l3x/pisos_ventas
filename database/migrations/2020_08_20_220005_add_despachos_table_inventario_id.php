<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDespachosTableInventarioId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('despachos', function (Blueprint $table) {
            //
           // $table->unsignedBigInteger('inventario_id');

             //$table->foreign('inventario_id')->references('id')->on('inventarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('despachos', function (Blueprint $table) {
            //
           // $table->dropForeign('despachos_inventario_id_foreign');
        });
    }
}
