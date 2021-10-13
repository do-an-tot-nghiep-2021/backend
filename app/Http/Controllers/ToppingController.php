<?php

namespace App\Http\Controllers;

use App\Models\ToppingModel;
use Illuminate\Http\Request;

class ToppingController extends Controller
{
    public function index()
    {
        $Toppings = ToppingModel::all();
        return response()->json($Toppings);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $Topping = ToppingModel::create($data);
        return response()->json($Topping);
    }

    public function show($id)
    {
        $Topping = ToppingModel::find($id);
        return response()->json($Topping);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $Topping = ToppingModel::find($id);
        $Topping->update($data);
        return response()->json($Topping);
    }

    public function destroy($id)
    {
        $Topping = ToppingModel::find($id);
        $deleted = $Topping->delete();
        return response()->json($deleted);
    }
}
