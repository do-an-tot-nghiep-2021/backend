<?php

namespace App\Http\Controllers;

use App\Models\ClassroomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = ClassroomModel::all();
        $classrooms->load('building');
        return response()->json($classrooms);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:classroom|max:255',
            'building_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            ClassroomModel::create($data);
            return response()->json(true);
        }

    }

    public function show($id)
    {
        $classroom = ClassroomModel::find($id);
        return response()->json($classroom);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', Rule::unique('classroom')->ignore($id), ],
            'building_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            $classroom = ClassroomModel::find($id);
            $classroom->update($data);
            return response()->json(true);
        }
    }

    public function destroy($id)
    {
        $classroom = ClassroomModel::find($id);
        $deleted = $classroom->delete();
        return response()->json($deleted);
    }
}
