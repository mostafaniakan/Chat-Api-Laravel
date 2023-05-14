<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestUser;
use App\Http\Requests\RequestLoginUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(RequestUser $request)
    {
        $phone = $request->input('phone');
        return view('code', compact('phone'));
    }

    public function login(RequestLoginUser $request)
    {
        $phone = $request->input('phoneLogin');
        return view('code', compact('phone'));
    }
}
