<?php

namespace App\Http\Controllers;

use App\Models\BuildingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = BuildingModel::all();
        return response()->json($buildings);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $building = BuildingModel::create($data);
        return response()->json($building);
    }

    public function show($id)
    {
        $building = BuildingModel::find($id);
        return response()->json($building);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $building = BuildingModel::find($id);
        $building->update($data);
        return response()->json($building);
    }

    public function destroy($id)
    {
        DB::table('classroom')->where('building_id', $id)->delete();
        $building = BuildingModel::find($id);
        $deleted = $building->delete();
        return response()->json($deleted);
    }
}
