<?php

namespace App\Http\Controllers;

use App\Models\OrderDetailModel;
use App\Models\OrderDetailToppingModel;
use App\Models\OrderDetailTypeModel;
use App\Models\OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(false);
            } else {
                $orders = OrderModel::all();
                $orders->load('building');
                $orders->load('classroom');
                $orders->load('user');
                foreach ($orders as $order) {
                    $status = config('common.status');
                    $payment = config('common.payment');
                    $order->status = $status[$order->status];
                    $order->payment = $payment[$order->payment];
                    $detail = DB::table('order_detail')->where('order_id', $order->id)->get();
                    $order->products = $detail;
                    foreach ($order->products as $value) {
                        $topping = DB::table('order_detail_topping')->where('order_detail_id', $value->id)->get();
                        $value->topping = $topping;
                    }
                }
                return response()->json($orders);
            }
        }else{
            return response()->json(false);
        }
    }

    public function store(Request $request)
    {
        $order = OrderModel::create([
            'user_id' => $request->userId,
            'building' => $request->building,
            'classroom' => $request->classroom,
            'item_total'=> $request->itemCount,
            'price_total'=>$request->total,
            'payment'=>$request->payment,
            'note'=>$request->note
        ]);
        foreach($request->cartItems as $value){
            $order_detail =  OrderDetailModel::create([
                'order_id' => $order->id,
                'quantity' => $value['quantity'],
                'image' => $value['image'],
                'name'=> $value['name'],
                'price'=>$value['price'],
                'type'=>$value['type']
            ]);
            if (isset($value['topping'])) {
                foreach ($value['topping'] as $key) {
                    OrderDetailToppingModel::create([
                        'order_detail_id' => $order_detail->id,
                        'name' => $key,
                    ]);
                }
            }

        }

        return response()->json(["status" => true]);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        } else {
            if($request->status){
                $orders = DB::table('orders')->where('user_id', $request->id)->where('status', $request->status)->get();
                foreach ($orders as $order){
                    $status = config('common.status');
                    $payment = config('common.payment');
                    $order->status = $status[$order->status];
                    $order->payment = $payment[$order->payment];
                    $order->products = DB::table('order_detail')->where('order_id', $order->id)->get();
                    foreach ($order->products as $value){
                        $topping = DB::table('order_detail_topping')->where('order_detail_id', $value->id)->get();
                        $value->topping = $topping;
                    }
                    $order->building = DB::table('building')->where('id', $order->building)->get();
                    $order->classroom = DB::table('classroom')->where('id', $order->classroom)->get();
                }
                return response()->json($orders);
            }else {
                $orders = DB::table('orders')->where('user_id', $request->id)->get();
                foreach ($orders as $order) {
                    $status = config('common.status');
                    $payment = config('common.payment');
                    $order->status = $status[$order->status];
                    $order->payment = $payment[$order->payment];
                    $order->products = DB::table('order_detail')->where('order_id', $order->id)->get();
                    foreach ($order->products as $value) {
                        $topping = DB::table('order_detail_topping')->where('order_detail_id', $value->id)->get();
                        $value->topping = $topping;
                    }
                    $order->building = DB::table('building')->where('id', $order->building)->get();
                    $order->classroom = DB::table('classroom')->where('id', $order->classroom)->get();
                }
                return response()->json($orders);
            }
        }
    }

    public function showId($id)
    {
        $order = OrderModel::find($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $order = OrderModel::find($id);
        $order->update($data);
        return response()->json(true);
    }
}
