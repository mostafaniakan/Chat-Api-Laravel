<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//users
Route::post('/Register',[\App\Http\Controllers\AuthController::class,'store']);
Route::post('/Login',[\App\Http\Controllers\AuthController::class,'login'])->middleware('throttle:downloads');
//->middleware('throttle:3600,2');
Route::get('/Logout',[\App\Http\Controllers\AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/SearchUser/{id}',[\App\Http\Controllers\AuthController::class,'showUser'])->middleware('auth:sanctum');
Route::post('/Code',[\App\Http\Controllers\AuthController::class,'codeToken']);


//message
Route::post('/SendMessage',[\App\Http\Controllers\MessageController::class,'index'])->middleware('auth:sanctum');
Route::get('/ReceiveSentMessages/{room_id}',[\App\Http\Controllers\MessageController::class,'getMessage'])->middleware('auth:sanctum');
Route::post('/Received',[\App\Http\Controllers\MessageController::class,'receivedMessages'])->middleware('auth:sanctum');

//room
Route::get('/singleRoom/{id}',[\App\Http\Controllers\RoomController::class,'createRoomSingle'])->middleware('auth:sanctum');
Route::get('/showMyRoom',[\App\Http\Controllers\RoomController::class,'showMyRoom'])->middleware('auth:sanctum');
Route::get('/CreateRoomGroup/{name}',[\App\Http\Controllers\RoomController::class,'createRoomGroup'])->middleware('auth:sanctum');
Route::post('/AddUserGroup',[\App\Http\Controllers\RoomController::class,'addUser'])->middleware('auth:sanctum');
