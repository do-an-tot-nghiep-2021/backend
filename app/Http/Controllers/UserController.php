<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function index($token)
    {
        $user = JWTAuth::setToken($token)->toUser();

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $model = new User();
        $model->name = $request->name;
        $model->email = $request->email;
        $model->phone = $request->phone;
        $model->image = $request->image;
        $pass = Hash::make($request->password);
        $model->password = $pass;
        $model->save();
        if (($model->save()) == true) {
            $message = "Bạn đã đăng ký thành công!";
            $status = true;
            return response()->json(['message' => $message, 'status' => $status]);
        } else {
            $message = "Đăng ký thất bại!";
            $status = false;
            return response()->json(['message' => $message, 'status' => $status]);
        }
    }

    public function storeDataGoogle(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        if($user){
            $user = DB::table('users')->where('email', $request->email)->first();
            return  response()->json($user);
        }else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->google_id = $request->googleId;
            $user->image = $request->imageUrl;
            $user->phone = "";
            $user->password = "";
            $user->save();
            return  response()->json($user);
        }
    }

    public function getProfileGoogle(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => false]);
        } else {
            $user = User::find($id);
            return response()->json($user);
        }
    }
    public function updateProfileGoogle(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'google_id' => 'required',
            'phone' => [ 'required', Rule::unique('users')->ignore($id), ],
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => "Số điện thoại đã tồn tại, vui lòng chọn số khác!"]);
        } else {
            $user = User::find($id);
            $user->fill($request->all());
            $user->save();
            $userNew = User::find($id);
            return response()->json(["status" => true, "user" => $userNew]);
        }

    }

    public function getAll(Request $request){
        if ($request->user['role'] == 10) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(["status" => false]);
            } else {
                if ($request->role == 0) {
                    $users = User::orderByDesc('created_at')->get();
                    foreach ($users as $items){
                        $items->date_create = Carbon::createFromFormat('Y-m-d H:i:s', $items->created_at)->format('d-m-Y');
                        $items->time_create = Carbon::createFromFormat('Y-m-d H:i:s', $items->created_at)->format('H:i:s');
                    }
                    return response()->json($users);
                }else{
                    $users = DB::table('users')
                        ->where('role', $request->role)
                        ->orderByDesc('created_at')
                        ->get();
                    foreach ($users as $items){
                        $items->date_create = Carbon::createFromFormat('Y-m-d H:i:s', $items->created_at)->format('d-m-Y');
                        $items->time_create = Carbon::createFromFormat('Y-m-d H:i:s', $items->created_at)->format('H:i:s');
                    }
                    return response()->json($users);
                }
            }
        }else {
            return response()->json(["status" => false]);
        }
    }

}
