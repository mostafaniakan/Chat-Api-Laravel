<?php

namespace App\Http\Controllers;


use App\Http\Requests\RequestUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{

 public function home(){

     return view('home');
 }

}
