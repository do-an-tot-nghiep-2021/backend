<?php

namespace App\Http\Controllers;

use App\Models\BuildingModel;
use App\Models\CategoryModel;
use App\Models\ClassroomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = BuildingModel::all();
        return response()->json($buildings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:building|max:255',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            BuildingModel::create($data);
            return response()->json(true);
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
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', Rule::unique('building')->ignore($id),],

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            $building = BuildingModel::find($id);
            $building->update($data);
            return response()->json(true);
        }
    }

    public function destroy($id)
    {
        DB::table('classroom')->where('building_id', $id)->delete();
        $building = BuildingModel::find($id);
        $deleted = $building->delete();
        return response()->json($deleted);
    }
}
