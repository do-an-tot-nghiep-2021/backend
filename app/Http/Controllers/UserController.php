<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        if (($model->save()) == true){
            $message = "Bạn đã đăng ký thành công!";
            $status = true;
            return response()->json(['message' => $message, 'status' => $status]);
        }else{
            $message = "Đăng ký thất bại!";
            $status = false;
            return response()->json(['message' => $message, 'status' => $status]);
        }
    }
}
