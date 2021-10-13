<?php

namespace App\Http\Controllers;

use App\Models\ProductsModel;
use App\Models\ProductToppingModel;
use App\Models\ToppingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index()
    {
        $products = ProductsModel::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $product = ProductsModel::create([
            'name' => $request->name,
            'image' => $request->image,
            'price'=> $request->price,
            'description'=>$request->description,
            'point'=>$request->point,
            'cate_id'=>$request->cate_id
        ]);
        if (isset($request->topping_id)) {
            $product->productTopping()->attach(
                $request->topping_id
            );
        }
        if (isset($request->type_id)) {
            $product->productType()->attach(
                $request->type_id
            );
        }

        return response()->json($product);
    }

    public function show($id)
    {
//        $productTopping = DB::table('product_topping')->where('product_id', $id)->get();
//        foreach ($productTopping as $topping){
//            $model = DB::table('topping')->where('id', $topping->topping_id)->get();
//        }
//        $topping = DB::table('topping')->where('topping_id', $productTopping->id)->get();
        $product = ProductsModel::find($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = ProductsModel::find($id);
        $product->update($data);
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
