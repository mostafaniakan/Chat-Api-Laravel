<?php

namespace App\Http\Controllers;

use App\Http\Resources\KeyResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\UserResource;
use App\Models\Key;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class RoomController extends Controller
{
    use ApiResponse;

    public function createRoomSingle($id)
    {
        $userId = auth()->user()->id;

        $user = User::all()->where('id', $id)->first();

        $keyUser = Key::where('user_id', $userId)->exists();

        if ($keyUser != true) {
            $room = Key::where('room_id', $keyUser->room_id)->where('user_id', $user->id)->get();
            return KeyResource::collection($room);

        } else {
            $room = Room::create([
                'name' => '',
                'type' => 'single',
            ])->first();

            Key::create([
                'user_id' => $userId,
                'room_id' => $room->id,]);

            Key::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
            ]);
            return new RoomResource($room);
        }

    }

    public function showMyRoom()
    {

        $user_id = auth()->user()->id;

        $myRoom = Key::where('user_id', $user_id)->get();

        return KeyResource::collection($myRoom);

    }


    public function createRoomGroup($name)
    {

        $user_id = auth()->user()->id;
        $validation = Validator([
            'name' => "required|string"
        ]);
        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(), 400);
        }

        $roomGroup = Room::create([
            'name' => $name,
            'type' => 'group',
            'admin' => $user_id,

        ]);
        Key::create([
            'user_id' => $user_id,
            'room_id' => $roomGroup->id,]);

        return new RoomResource($roomGroup);
    }

    public function addUser(Request $request)
    {
        $loginId = auth()->user()->id;
        $validation = Validator::make($request->all(), [
            $request->validate([
                'user_id' => 'required',
                'room_id' => 'required',
            ])
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(), 400);
        }

        $room = Room::where('id', $request->room_id)->where('admin', $loginId)->exists();
        $checkUser = Key::where('room_id', $request->room_id)->where('user_id', $request->user_id)->exists();

        if ($room == true && $checkUser == false) {
            Key::create([
                'user_id' => $request->user_id,
                'room_id' => $request->room_id,
            ]);
            $userAddId = $request->user_id;
            $user = User::where('id', $userAddId)->first();

            return $this->successResponse('user add',$user->name,200);
        } else {
            return $this->errorResponse('You are not an admin or there is a user', 400);
        }
    }
}
