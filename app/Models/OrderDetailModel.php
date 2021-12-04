<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetailModel extends Model
{
    protected $table = "order_detail";

    protected $fillable = [
        'name','image','price','quantity','order_id','type', 'product_id'
    ];
}
