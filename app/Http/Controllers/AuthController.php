<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Key;
use App\Models\Room;
use App\Models\User;
use App\Notifications\Sms;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;
    use Notifiable;

//    check user
    public function checkPhoneUser($request)
    {

//        validation phone
        $validation = Validator::make($request->all(), [
            $request->validate([
                'phones' => 'required|min:9|numeric',
            ])
        ]);
        if ($validation->fails()) {
            return response()->json($validation->messages(), 422);
        }

//        find phone number
        $user = User::where('phones', $request->phones)->first();
        if (!$user) {
            return response()->json('user not fount', 401);
        }


        if ($user) {
            return $user;
        }
    }

//search User
    public function showUser($phones)
    {
        $user = User::all()->where('phones', $phones)->first();
        if($user != null) {
            return new UserResource($user);
        }else{
          return  $this->errorResponse('user not found ',404);
        }
    }

//    create user
    public function store(Request $request)
    {

        $phone = $this->checkPhoneUser($request);
        if (isset($phone->phones)) {
            return $this->errorResponse('The user exists', 409);
        } else {
            $validation = Validator::make($request->all(), [
                $request->validate([
                    'name' => 'required',
                    'phones' => 'required|min:9|numeric|unique:users|',
                    'email' => 'email|required|unique:users,email',
                ])
            ]);
            if ($validation->fails()) {
                return $this->errorResponse($validation->messages(), 422);
            }


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phones' => $request->phones,
                'codes' => rand(123, 10000),
            ]);

//        send sms
            Notification::send($user, new Sms($user, $user->codes));
            return $this->successResponse('User is registered', $user->phones, 200);
        }
    }


//    log in
    public function login(Request $request)
    {
        $phone = $this->checkPhoneUser($request);

        if (isset($phone->phones)) {
            $user = User::where('phones', $request->phones)->first();
            $sendCode = $user->codes;

            Notification::send($user, new Sms($user, $sendCode));
            return $this->successResponse('Code Send ', $user->phones, 200);
        } else {
            return $this->errorResponse('user not found', 404);
        }
//        if (!Hash::check($request->password, $user->password)) {
//            return response()->json('password is incorrect', 401);
//        }

//        check phone number and get user code

    }


//   create code token
    public function codeToken(Request $request)
    {

//        check phone number

        $user = $this->checkPhoneUser($request);
        if (isset($user->phones)) {
//        validate input
            $validationCode = Validator::make($request->all(), [
                'codes' => 'required',
            ]);
            if ($validationCode->fails()) {
                return response()->json($validationCode->messages(), 422);
            }

//      get info user
            if (User::where('codes', '=', $request->codes)->first()) {

//            update code token
                User::where('phones', $request->phones)->update([
                    'codes' => rand(123, 10000)
                ]);

                //        create token
                $token = $user->createToken('myApp')->plainTextToken;
                User::where('phones',$request->phones)->update([
                    'remember_token'=>$token,
                ]);
                return response()->json([
                    'user' => $user,
                    'Token' => $token,
                    'code'=>201,
                ], 201);

            } else {
                return $this->errorResponse('Incorrect code', 401);
            }
        } else {
            return $this->errorResponse('user not fount', 404);
        }

    }

//   log out
    public function logout()
    {
        auth()->user()->tokens()->delete();
//     find user login;
        return response()->json('user logout');
    }

    public function checkCode(Request $request){

        $token=User::where('remember_token',$request->token)->where('phones',$request->phones)->first();
        if( $token != null){
            return $this->successResponse('token exist',$token,200);
        }else{
            return  $this->errorResponse('token not exist ',404);
        }
    }
}
