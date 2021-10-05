<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $cates = CategoryModel::all();
        return response()->json($cates);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $cate = CategoryModel::create($data);
        return response()->json($cate);
    }

    public function show($id)
    {
        $cate = CategoryModel::find($id);
        return response()->json($cate);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $cate = CategoryModel::find($id);
        $cate->update($data);
        return response()->json($cate);
    }

    public function destroy($id)
    {
        $cate = CategoryModel::find($id);
        $deleted = $cate->delete();
        return response()->json($deleted);
    }
}
