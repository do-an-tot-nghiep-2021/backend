<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use App\Mail\ResetPass;
class AdminController extends Controller
{

    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)){
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'token' => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
    public function getResetPass(Request $request)
    {
        DB::table('reset_password')->where('email', $request->email)->delete();
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response(['message' => "Lỗi",'status' => false]);
        }
        $token = rand(10, 100000);
        $email = $request->email;
        $datamodel = [
            'email' => $email,
            'token' => $token
        ];
        try {
            DB::table('reset_password')->insert($datamodel);
            Mail::to($user->email)->send(new ResetPass($token));
            return response([
                'message' => "Vui lòng kiểm tra email để nhận mã code!",
                'status' => true,
                'email' => $request->email
            ]);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function resetPass(Request $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

        $checkemail = DB::table('reset_password')->where('email', $email)->first();
        $checktoken = DB::table('reset_password')->where('token', $token)->first();

        if (!$checkemail) {
            return response([
                'message' => "Email không tồn tại",
                'status' => false
            ]);
        }
        if (!$checktoken) {
            return response([
                'message' => "Mã code không tồn tại",
                'status' => false
            ]);
        }
        DB::table('users')->where('email', $email)->update(['password' => $password]);
        DB::table('reset_password')->where('email', $email)->delete();

        return response([
            'message ' => "Đổi mmật khẩu thành công",
            'status' => true
        ]);
    }


}
