<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    protected $table = "products";
    protected $fillable = [
        'name', 'image', 'price', 'description', 'point', 'cate_id'
    ];
}
