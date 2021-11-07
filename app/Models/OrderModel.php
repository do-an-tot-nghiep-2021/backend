<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = "orders";

    protected $fillable = [
        'building','classroom','item_total','price_total','status','payment'
    ];

    public function topping(){
        return $this->belongsTo(OrderDetailToppingModel::class, 'order_detail_id');
    }
}
