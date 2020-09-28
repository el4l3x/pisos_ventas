<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    //
    public function piso_venta()
    {
    	return $this->belongsTo('App\Piso_venta');
    }

    public function detalles()
    {
        return $this->morphMany('App\Detalle_venta', 'taggable');
    }
}
