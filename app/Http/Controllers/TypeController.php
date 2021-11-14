<?php

namespace App\Http\Controllers;

use App\Models\TypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TypeController extends Controller
{
    public function index()
    {
        $types = TypeModel::all();
        return response()->json($types);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:types|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            TypeModel::create($data);
            return response()->json(true);
        }
    }

    public function show($id)
    {
        $type = TypeModel::find($id);
        return response()->json($type);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', Rule::unique('types')->ignore($id),],
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            $type = TypeModel::find($id);
            $type->update($data);
            return response()->json(true);
        }
    }

    public function destroy($id)
    {
        $type = TypeModel::find($id);
        $deleted = $type->delete();
        return response()->json($deleted);
    }
}
