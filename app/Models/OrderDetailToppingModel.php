<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailToppingModel extends Model
{
    protected $table = "order_detail_topping";

    protected $fillable = [
        'name','order_detail_id'
    ];
}
