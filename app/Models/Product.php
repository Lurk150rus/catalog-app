<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    public function price()
    {
        return $this->hasOne(Price::class, 'id_product');
    }
}

