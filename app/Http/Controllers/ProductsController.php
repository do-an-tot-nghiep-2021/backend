<?php

namespace App\Http\Controllers;

use App\Models\ProductsModel;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = ProductsModel::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $product = ProductsModel::create($data);
        return response()->json($product);
    }

    public function show($id)
    {
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
        $deleted = $product->delete();
        return response()->json($deleted);
    }
}
