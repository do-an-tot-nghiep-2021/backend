<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    protected $table = "products";
    protected $fillable = [
        'name', 'image', 'price', 'cate_id', 'status'
    ];

    public function productTopping(){
        return $this->belongsToMany(ToppingModel::class,'product_topping','product_id', 'topping_id');
    }

    public function productType(){
        return $this->belongsToMany(TypeModel::class,'product_types','product_id', 'type_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'cate_id');
    }
}
