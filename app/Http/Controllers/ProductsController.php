<?php

namespace App\Http\Controllers;

use App\Models\ClassroomModel;
use App\Models\ProductsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function showProductCate($id)
    {
        $products = ProductsModel::with('category')->where('cate_id', $id)->get();
        return response()->json($products);
    }

    public function getKeyword($kw)
    {
        $products = ProductsModel::where('name', 'like', "%".$kw."%")->get();
        $products->load('category');
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products|max:255',
            'image' => 'required',
            'price' => 'required',
            'description' => 'required',
            'cate_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
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
            return response()->json(true);
        }



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
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', Rule::unique('products')->ignore($id), ],
            'image' => 'required',
            'price' => 'required',
            'description' => 'required',
            'cate_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
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
            return response()->json(true);
        }
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
