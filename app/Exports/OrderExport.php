<?php

namespace App\Exports;

use App\Models\OrderModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;


class OrderExport implements FromView
{
    /**
    *
    */
    use Exportable;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function view(): View
    {
        if ($this->date == 1) {
            $orders = OrderModel::whereDate('created_at', Carbon::today())->get();
        }else{
            $orders = OrderModel::whereDate('created_at', ">=" , Carbon::now()->subDays($this->date))->get();
        }
        $orders->load('user');
        foreach ($orders as $order) {
            $status = config('common.status');
            $payment = config('common.payment');
            $order->status = $status[$order->status];
            $order->payment = $payment[$order->payment];
            $building = DB::table('building')->where('id', $order->building)->first();
            $order->building_name = $building->name;
            $classroom = DB::table('classroom')->where('id', $order->classroom)->first();
            $order->class_name = $classroom->name;
            $order->date_create = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d-m-Y');
            $order->time_create = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('H:i:s');
            $detail = DB::table('order_detail')->where('order_id', $order->id)->get();
            $order->products = $detail;
            foreach ($order->products as $value) {
                $topping = DB::table('order_detail_topping')->where('order_detail_id', $value->id)->get();
                $value->topping = $topping;
            }
        }
        return view('export-excel', [
            'orders' => $orders
        ]);

    }
}
