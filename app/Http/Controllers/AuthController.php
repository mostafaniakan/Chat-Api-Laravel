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

class AuthController extends Controller
{
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
    public function showUser($id)
    {
        $user = User::all()->where('id', $id)->first();
        return new UserResource($user);
    }

//    create user
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            $request->validate([
                'name' => 'required',
                'phones' => 'required|min:9|numeric|unique:users',
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
    }


//    log in
    public function login(Request $request)
    {

        $phone = $this->checkPhoneUser($request);

        if (isset($phone->phones)) {
            $user = User::where('phones', $request->phones)->first();
            $sendCode = $user->codes;
            Notification::send($user, new Sms($user, $sendCode));
        } else {
            return 'user not fount';
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
                return response()->json([
                    'user' => $user,
                    'Token' => $token
                ], 201);
            } else {
                return response()->json('Incorrect code ');
            }
        } else {
            return 'user not fount';
        }

    }

//   log out
    public function logout()
    {
        auth()->user()->tokens()->delete();
//     find user login;
        return response()->json('user logout');
    }

}
