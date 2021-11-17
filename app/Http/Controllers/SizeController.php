<?php

namespace App\Http\Controllers;

use App\Models\SizeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

class SizeController extends Controller
{
    public function index()
    {
        $Sizes = SizeModel::all();
        return response()->json($Sizes);
    }

    public function store(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => 'required|unique:size|max:255',
                'price' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                SizeModel::create($data);
                return response()->json(["status" => true, "message" => "Thêm thành công"]);
            }
        }else {
            return response()->json(["status" => false, "message" => "Thêm thất bại"]);
        }
    }

    public function show($id)
    {
        $Size = SizeModel::find($id);
        return response()->json($Size);
    }

    public function update(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'user' => 'required',
                'name' => ['required', Rule::unique('size')->ignore($id),],
                'price' => 'required',

            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $model = SizeModel::find($id);
                $model->fill($request->all());
                $model->save();
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
                $Size = SizeModel::find($id);
                $Size->delete();
                return response()->json(true);
            }
        }else{
            return response()->json(false);
        }
    }
}
