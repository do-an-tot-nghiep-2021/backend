<?php

namespace App\Http\Controllers;

use App\Models\ClassroomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        if ($request->build == 0){
            $classrooms = ClassroomModel::orderByDesc('created_at')->get();
        }else{
            $classrooms = ClassroomModel::orderByDesc('created_at')->where('building_id', $request->build)->get();
        }
        $classrooms->load('building');
        return response()->json($classrooms);
    }

    public function store(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => 'required|unique:classroom|max:255',
                'building_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                ClassroomModel::create($data);
                return response()->json(["status" => true, "message" => "Thêm thành công"]);
            }
        }else {
            return response()->json(["status" => false, "message" => "Thêm thất bại"]);
        }
    }

    public function show($id)
    {
        $classroom = ClassroomModel::find($id);
        return response()->json($classroom);
    }

    public function update(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => [ 'required', Rule::unique('classroom')->ignore($id), ],
                'building_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                $classroom = ClassroomModel::find($id);
                $classroom->update($data);
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
                $classroom = ClassroomModel::find($id);
                $classroom->delete();
                return response()->json(true);
            }
        }else{
            return response()->json(false);
        }
    }
}
