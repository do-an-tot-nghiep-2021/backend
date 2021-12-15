<?php

namespace App\Http\Controllers;

use App\Models\BuildingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = BuildingModel::orderByDesc('created_at')->get();
        return response()->json($buildings);
    }

    public function store(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => 'required|unique:building|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                BuildingModel::create($data);
                return response()->json(["status" => true, "message" => "Thêm thành công"]);
            }
        }else {
            return response()->json(["status" => false, "message" => "Thêm thất bại"]);
        }

    }

    public function show($id)
    {
        $building = BuildingModel::find($id);
        return response()->json($building);
    }

    public function showClass($id)
    {
        $class = DB::table('classroom')->where('building_id', $id)->get();
        return response()->json($class);
    }

    public function update(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => [ 'required', Rule::unique('building')->ignore($id),],
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                $building = BuildingModel::find($id);
                $building->update($data);
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
                DB::table('classroom')->where('building_id', $id)->delete();
                $building = BuildingModel::find($id);
                $building->delete();
                return response()->json(true);
            }
        }else{
            return response()->json(false);
        }
    }
}
