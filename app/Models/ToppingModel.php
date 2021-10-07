<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToppingModel extends Model
{
    protected $table = "topping";
    protected $fillable = [
        'name','price'
    ];
}
