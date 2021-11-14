<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ClassroomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $cates = CategoryModel::all();
        return response()->json($cates);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'image' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            CategoryModel::create($data);
            return response()->json(true);
        }
    }

    public function show($id)
    {
        $cate = CategoryModel::find($id);
        return response()->json($cate);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', Rule::unique('categories')->ignore($id),],
            'image' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            $cate = CategoryModel::find($id);
            $cate->update($data);
            return response()->json(true);
        }
    }

    public function destroy($id)
    {
        DB::table('products')->where('cate_id', $id)->delete();
        $cate = CategoryModel::find($id);
        $deleted = $cate->delete();
        return response()->json($deleted);
    }
}
