<?php

namespace App\Http\Controllers;

use App\Models\ToppingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ToppingController extends Controller
{
    public function index()
    {
        $Toppings = ToppingModel::all();
        return response()->json($Toppings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:topping|max:255',
            'price' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $data = $request->all();
            ToppingModel::create($data);
            return response()->json(true);
        }
    }

    public function show($id)
    {
        $Topping = ToppingModel::find($id);
        return response()->json($Topping);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => [ 'required', Rule::unique('topping')->ignore($id),],
            'price' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }
        else{
            $model = ToppingModel::find($id);
            $model->fill($request->all());
            $model->save();
            return response()->json(true);
        }
    }

    public function destroy($id)
    {
        $Topping = ToppingModel::find($id);
        $deleted = $Topping->delete();
        return response()->json($deleted);
    }
}
