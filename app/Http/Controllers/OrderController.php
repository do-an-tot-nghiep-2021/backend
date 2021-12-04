<?php

namespace App\Http\Controllers;

use App\Mail\OrderMail;
use App\Models\OrderDetailModel;
use App\Models\OrderDetailToppingModel;
use App\Models\OrderDetailTypeModel;
use App\Models\OrderModel;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

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
                    if ($order->status == 1){
                        $order->color = "m-badge m-badge--warning m-badge--wide";
                    }elseif ($order->status == 2){
                        $order->color = "m-badge m-badge--info m-badge--wide";
                    }elseif ($order->status == 3){
                        $order->color = "m-badge m-badge--primary m-badge--wide";
                    }elseif ($order->status == 4){
                        $order->color = "m-badge m-badge--success m-badge--wide";
                    }else{
                        $order->color = "m-badge m-badge--danger m-badge--wide";
                    }
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

    public function getDate(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(false);
            } else {
                if ($request->date == 1){
                    $orders = OrderModel::whereDate('created_at', Carbon::today())->get();
                }
                else{
                    $orders = OrderModel::whereDate('created_at', ">=" , Carbon::now()->subDays($request->date))->get();
                }
                $orders->load('building');
                $orders->load('classroom');
                $orders->load('user');
                foreach ($orders as $order) {
                    $status = config('common.status');
                    if ($order->status == 1){
                        $order->color = "m-badge m-badge--warning m-badge--wide";
                    }elseif ($order->status == 2){
                        $order->color = "m-badge m-badge--info m-badge--wide";
                    }elseif ($order->status == 3){
                        $order->color = "m-badge m-badge--primary m-badge--wide";
                    }elseif ($order->status == 4){
                        $order->color = "m-badge m-badge--success m-badge--wide";
                    }else{
                        $order->color = "m-badge m-badge--danger m-badge--wide";
                    }
                    $payment = config('common.payment');
                    $order->status = $status[$order->status];
                    $order->payment = $payment[$order->payment];
                    $order->date_create = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d-m-Y');
                    $order->time_create = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('H:i:s');
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
        if(($request->voucher) != ""){
            $voucher = DB::table('voucher_user')->where('user_id', $request->userId)->where('voucher_id', $request->voucher);
            $voucher->delete();
        }
        $users_data = User::find($request->userId);
        $users_data->point = $users_data->point + $request->total;
        $users_data->save();
        $order = OrderModel::create([
            'user_id' => $request->userId,
            'building' => $request->building,
            'classroom' => $request->classroom,
            'item_total'=> $request->itemCount,
            'price_total'=>$request->total,
            'payment'=>$request->payment,
            'note'=>$request->note,
            'phone'=>$request->phone
        ]);
        foreach($request->cartItems as $value){
            $order_detail =  OrderDetailModel::create([
                'order_id' => $order->id,
                'product_id' => $value['id'],
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
        $building = DB::table('building')->where('id', $order->building)->first();
        $classroom = DB::table('classroom')->where('id', $order->classroom)->first();

        $user = User::find($order->user_id);
        $order_send_product = DB::table('order_detail')->where('order_id', $order->id)->get();
        foreach ($order_send_product as $items) {
            $topping = DB::table('order_detail_topping')->where('order_detail_id', $items->id)->get();
            $items->topping = $topping;
        }
        $payment = config('common.payment');
        $send_order = new \stdClass();
        $send_order->total_price = $order->price_total;
        $send_order->total_item = $order->item_total;
        $send_order->payment = $payment[$order->payment];
        $send_order->classroom = $classroom->name;
        $send_order->building = $building->name;
        $send_order->product = $order_send_product;
        Mail::to($user->email)->send(new OrderMail($send_order));

        return response()->json(["status" => true]);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        } else {
            if($request->status == 0){
                $orders = DB::table('orders')->where('user_id', $request->id)->get();
            }else {
                $orders = DB::table('orders')->where('user_id', $request->id)->where('status', $request->status)->get();
            }
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

    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        }else{
            $order = OrderModel::find($request->id);
            if ($order->user_id == $request->user_id) {
                $order->status = $request->status;
                $order->save();
                return response()->json(["status" => true]);
            }else{
                return response()->json(["status" => false]);
            }
        }
    }
}
