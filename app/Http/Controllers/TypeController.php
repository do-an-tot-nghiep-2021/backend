<?php

namespace App\Http\Controllers;

use App\Models\TypeModel;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = TypeModel::all();
        return response()->json($types);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $type = TypeModel::create($data);
        return response()->json($type);
    }

    public function show($id)
    {
        $type = TypeModel::find($id);
        return response()->json($type);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $type = TypeModel::find($id);
        $type->update($data);
        return response()->json($type);
    }

    public function destroy($id)
    {
        $type = TypeModel::find($id);
        $deleted = $type->delete();
        return response()->json($deleted);
    }
}
