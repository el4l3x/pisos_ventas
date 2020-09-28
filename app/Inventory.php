<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //
    public function product()
    {
        return $this->hasOne('App\Product', 'inventory_id', 'id');
    }
}
