<?php

namespace App\Http\Controllers;

use App\Models\SizeModel;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $Sizes = SizeModel::all();
        return response()->json($Sizes);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $Size = SizeModel::create($data);
        return response()->json($Size);
    }

    public function show($id)
    {
        $Size = SizeModel::find($id);
        return response()->json($Size);
    }

    public function update(Request $request, $id)
    {
        $model = SizeModel::find($id);
        $model->fill($request->all());
        $model->save();
        return response()->json($model);
    }

    public function destroy($id)
    {
        $Size = SizeModel::find($id);
        $deleted = $Size->delete();
        return response()->json($deleted);
    }
}
