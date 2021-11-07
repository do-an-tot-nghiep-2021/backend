<?php

namespace App\Http\Controllers;

use App\Models\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index()
    {
        $products = ProductsModel::all();
        $products->load('category');
        $products->load('productTopping');
        $products->load('productType');
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $product = ProductsModel::create([
            'name' => $request->name,
            'image' => $request->image,
            'price'=> $request->price,
            'description'=>$request->description,
            'cate_id'=>$request->cate_id
        ]);
        if (($request->topping_id) !== false) {
            $product->productTopping()->attach(
                $request->topping_id
            );
        }
        if (($request->type_id) !== false) {
            $product->productType()->attach(
                $request->type_id
            );
        }

        return response()->json($product);
    }

    public function show($id)
    {
        $product = ProductsModel::find($id);
        $product->load('category');
        $product->load('productTopping');
        $product->load('productType');
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = ProductsModel::find($id);
        if (($request->product_topping) !== ""){
            $product->productTopping()->sync(
                $request->product_topping
            );
        }
        if (($request->product_topping) !== "") {
            $product->productType()->sync(
                $request->product_type
            );
        }
        $product->fill($request->all());
        $product->save();
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = ProductsModel::find($id);
        $product->productTopping()->detach();
        $product->productType()->detach();
        $deleted = $product->delete();
        return response()->json($deleted);
    }

}
