<?php

namespace App\Http\Controllers;

use App\Models\OrderDetailModel;
use App\Models\OrderDetailToppingModel;
use App\Models\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{


    public function store(Request $request)
    {
        $order = OrderModel::create([
            'building' => $request->building,
            'classroom' => $request->classroom,
            'item_total'=> $request->itemCount,
            'price_total'=>$request->total,
            'payment'=>$request->payment
        ]);

        foreach($request->cartItems as $value){
            $order_detail =  OrderDetailModel::create([
                'order_id' => $order->id,
                'quantity' => $value['quantity'],
                'image' => $value['image'],
                'name'=> $value['name'],
                'price'=>$value['price'],
            ]);
            foreach($value['topping'] as $key){
                OrderDetailToppingModel::create([
                    'order_detail_id' => $order_detail->id,
                    'name' => $key,
                ]);
            }
        }

        return response()->json(["status" => true]);
    }

    public function index($id)
    {
        $order = OrderModel::find($id);
        $order->detail = DB::table('order_detail')->where('order_id', $id)->get();
        foreach ($order->detail as $value){
            $topping = DB::table('order_detail_topping')->where('order_detail_id', $value->id)->get();
            $value->topping = $topping;
        }
        $order->detail->topping = $value->topping;
        return response()->json($order);

    }
}
