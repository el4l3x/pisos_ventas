<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDespachoDetallesTableInventoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('despacho_detalles', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('inventory_id');

            $table->foreign('inventory_id')->references('id')->on('inventories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('despacho_detalles', function (Blueprint $table) {
            //
            $table->dropForeign('despacho_detalles_inventory_id_foreign');
        });
    }
}
