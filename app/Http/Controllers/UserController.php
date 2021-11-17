<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;

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
    public function loginUrl()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
//        return Socialite::driver('google')->stateless()->redirect();
    }
    public function loginCallback()
    {
//        $googleUser = Socialite::driver('google')->stateless()->user();
//        $user = null;
//
//        DB::transaction(function () use ($googleUser, &$user) {
//            $socialAccount = Socialite::firstOrNew(
//                ['social_id' => $googleUser->getId(), 'social_provider' => 'google'],
//                ['social_name' => $googleUser->getName()]
//            );
//
//            if (!($user = $socialAccount->user)) {
//                $user = User::create([
//                    'email' => $googleUser->getEmail(),
//                    'name' => $googleUser->getName(),
//                ]);
//                $socialAccount->fill(['user_id' => $user->id])->save();
//            }
//        });
//
//        return response()->json([
//            'user' => new UserResource($user),
//            'google_user' => $googleUser,
//        ]);
        $data = Socialite::driver('google')->stateless()->user();
        dd($data);
        $user = User::where('email', '=', $data->email)->first();
        $pattentEmailFpt = "/[A-Za-z0-9_]@fpt.edu.vn/";
        if( !preg_match($pattentEmailFpt, $data->email)){
            return response()->json(['message','Vui lòng đăng nhập mail fpt.edu.vn']);
        }
        if (!$user) {
            $user = new User();
            $user->name = $data->name;
            $user->email = $data->email;
            $user->google_id = $data->id;
            $user->image = $data->avatar;
            $user->save();
            return  response()->json($user);
        }
        if($user){
            return response()->json($data);
        }

    }
}
