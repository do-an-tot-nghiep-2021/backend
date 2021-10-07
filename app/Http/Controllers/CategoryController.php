<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::table('products')->where('cate_id', $id)->delete();
        $cate = CategoryModel::find($id);
        $deleted = $cate->delete();
        return response()->json($deleted);
    }
}
