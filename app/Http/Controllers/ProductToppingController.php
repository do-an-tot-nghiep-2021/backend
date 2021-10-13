<?php

namespace App\Http\Controllers;

use App\Models\ProductsModel;
use App\Models\ToppingModel;
use Illuminate\Http\Request;

class ProductToppingController extends Controller
{
    public function index()
    {
        $product = ProductsModel::all();
        $Topping = ToppingModel::all();
        return response()->json(array('products' => $product, 'topping' => $Topping));
    }
}
