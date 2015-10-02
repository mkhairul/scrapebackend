<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'product';
    
    public function packages()
    {
        return $this->hasMany('App\Package', 'product_id', 'id');
    }
}
