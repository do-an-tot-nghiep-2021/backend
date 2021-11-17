<?php

namespace App\Http\Controllers;

use App\Models\ToppingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ToppingController extends Controller
{
    public function index()
    {
        $Toppings = ToppingModel::all();
        return response()->json($Toppings);
    }

    public function store(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => 'required|unique:topping|max:255',
                "price" => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                ToppingModel::create($data);
                return response()->json(["status" => true, "message" => "Thêm thành công"]);
            }
        }else {
            return response()->json(["status" => false, "message" => "Thêm thất bại"]);
        }

    }

    public function show($id)
    {
        $Topping = ToppingModel::find($id);
        return response()->json($Topping);
    }

    public function update(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'name' => [ 'required', Rule::unique('topping')->ignore($id),],
                'price' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                $type = ToppingModel::find($id);
                $type->update($data);
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
                $Topping = ToppingModel::find($id);
                $Topping->delete();
                return response()->json(true);
            }
        }else{
            return response()->json(false);
        }

    }
}
