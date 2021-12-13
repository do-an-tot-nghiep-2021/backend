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

    public function getKeyword(Request $request)
    {
        $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->get();
        if (isset($request->cate_id)) {
            $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->where('cate_id', $request->cate_id)->get();
        }
        if (isset($request->filter)) {
            if($request->filter == 1){
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->orderBy('name')->get();
            }else if($request->filter == 2){
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->orderByDesc('name')->get();
            }else if($request->filter == 3){
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->orderBy('price')->get();
            }else{
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->orderByDesc('price')->get();
            }
        }
        if ($request->filter > 0 && $request->cate_id > 0) {
            if($request->filter == 1){
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->where('cate_id', $request->cate_id)->orderBy('name')->get();
            }else if($request->filter == 2){
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->where('cate_id', $request->cate_id)->orderByDesc('name')->get();
            }else if($request->filter == 3){
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->where('cate_id', $request->cate_id)->orderBy('price')->get();
            }else{
                $products = ProductsModel::where('name', 'like', "%".$request->keyword."%")->where('cate_id', $request->cate_id)->orderByDesc('price')->get();
            }
        }
        $products->load('category');
        return response()->json($products);
    }

    public function store(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => 'required|unique:products|max:255',
                'image' => 'required',
                'price' => 'required',
                'cate_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã tồn tại, vui lòng chọn tên khác!"]);
            } else {
                $product = ProductsModel::create([
                    'name' => $request->name,
                    'image' => $request->image,
                    'price'=> $request->price,
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
                return response()->json(["status" => true, "message" => "Thêm thành công"]);
            }
        }else {
            return response()->json(["status" => false, "message" => "Thêm thất bại"]);
        }
    }

    public function show($id)
    {
        $product = ProductsModel::find($id);
        $product->product_cate = ProductsModel::with('category')->where('cate_id', $product->cate_id)->get();
        $product->load('category');
        $product->load('productTopping');
        $product->load('productType');
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => [ 'required', Rule::unique('products')->ignore($id), ],
                'image' => 'required',
                'price' => 'required',
                'cate_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã tồn tại, vui lòng chọn tên khác!"]);
            } else {
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
                return response()->json(["status" => true, "message" => "Cập nhật thành công"]);
            }
        }else{
            return response()->json(false);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(false);
            } else {
                $product = ProductsModel::find($id);
                $product->productTopping()->detach();
                $product->productType()->detach();
                $product->delete();
                return response()->json(true);
            }
        }else{
            return response()->json(false);
        }
    }

}
