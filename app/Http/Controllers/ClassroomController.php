<?php

namespace App\Http\Controllers;

use App\Models\ClassroomModel;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = ClassroomModel::all();
        return response()->json($classrooms);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $classroom = ClassroomModel::create($data);
        return response()->json($classroom);
    }

    public function show($id)
    {
        $classroom = ClassroomModel::find($id);
        return response()->json($classroom);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $classroom = ClassroomModel::find($id);
        $classroom->update($data);
        return response()->json($classroom);
    }

    public function destroy($id)
    {
        $classroom = ClassroomModel::find($id);
        $deleted = $classroom->delete();
        return response()->json($deleted);
    }
}
