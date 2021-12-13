<?php

namespace App\Http\Controllers;

use App\Models\VoucherModel;
use App\Models\VoucherUserHistoryModel;
use App\Models\VoucherUserModel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = VoucherModel::all();
        return response()->json($vouchers);
    }

    public function store(Request $request)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => 'required|unique:vouchers|max:255',
                "point" => 'required',
                "value" => 'required',
                "image" => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                VoucherModel::create($data);
                return response()->json(["status" => true, "message" => "Thêm thành công"]);
            }
        }else {
            return response()->json(["status" => false, "message" => "Thêm thất bại"]);
        }

    }

    public function show($id)
    {
        $voucher = VoucherModel::find($id);
        return response()->json($voucher);
    }

    public function update(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'name' => [ 'required', Rule::unique('vouchers')->ignore($id),],
                'point' => 'required',
                'value' => 'required',
                'image' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => "Tên đã được sử dụng"]);
            } else {
                $data = $request->all();
                $voucher = VoucherModel::find($id);
                $voucher->update($data);
                return response()->json(["status" => true, "message" => "Cập nhật thành công"]);
            }
        }else{
            return response()->json(false);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(false);
            } else {
                $voucher = VoucherModel::find($id);
                $voucher->delete();
                return response()->json(true);
            }
        }else{
            return response()->json(false);
        }
    }

    public function redeem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        } else {
            $user = User::find($request->user_id);
            $voucher = VoucherModel::find($request->id);
            $user_voucher = DB::table('voucher_user')->where('user_id', $request->user_id)->get();
            $user_voucher_history = DB::table('voucher_user_history')->where('user_id', $request->user_id)->get();
            $user_point = $user->point;
            $voucher_point = $voucher->point;
            if ($user_voucher->contains('voucher_id',$request->id)){
                return response()->json(["status" => false, "message" => "Bạn chỉ được đổi voucher này một lần!"]);
            }elseif ($user_voucher_history->contains('voucher_id',$request->id)){
                return response()->json(["status" => false, "message" => "Bạn chỉ được sử dụng voucher này một lần!"]);
            }else {
                if ($user_point >= $voucher_point) {
                    $user->point = $user_point - $voucher_point;
                    $user->save();
                    $modelVoucher = new VoucherUserModel();
                    $modelVoucher->user_id = $request->user_id;
                    $modelVoucher->voucher_id = $request->id;
                    $modelVoucher->save();

                    return response()->json(["status" => true, "message" => "Đổi voucher thành công!"]);
                } else {
                    return response()->json(["status" => false, "message" => "Bạn chưa đủ điểm để đổi voucher này!"]);
                }
            }

        }
    }

    public function voucherAccountId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(false);
        } else {
            if ($request->status == 1){
                $voucher_user = DB::table('voucher_user')->where('user_id', $request->user_id)->get();
            }else{
                $voucher_user = DB::table('voucher_user_history')->where('user_id', $request->user_id)->get();
            }
            foreach ($voucher_user as $items){
                $items->voucher = DB::table('vouchers')->where('id', $items->voucher_id)->first();
            }
            return response()->json($voucher_user);
        }
    }
}
