<?php

namespace App\Http\Controllers;

use App\Http\Resources\KeyResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\UserResource;
use App\Models\Key;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use function MongoDB\BSON\toJSON;

class RoomController extends Controller
{
    use ApiResponse;

    public function createRoomSingle($id)
    {

        $userId = auth()->user()->id;

        $user = User::all()->where('id', $id)->first();

//        $keyUser = Key::where('user_id', $user->id)->first();


        if ($user != null) {

            $roomUser = DB::table('users')->join('keys', 'users.id', '=', 'keys.user_id')
                ->where('users.id', $userId)->pluck('keys.room_id');

            $roomClient = DB::table('keys')->where('user_id', $user->id)->whereIn('keys.room_id', $roomUser)
                ->join('rooms', 'keys.room_id', '=', 'rooms.id')
                ->select('keys.user_id', 'keys.room_id', 'rooms.type')->get();


            if (count($roomClient) != 0) {
                return $this->successResponse('ok', $roomClient, 200);

            } else {

                $room = Room::create([
                    'name_room' => '',
                    'type' => 'single',
                    'admin' => 0,
                ]);
     
                Key::create([
                    'user_id' => $userId,
                    'room_id' => $room->id,]);

                Key::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                ]);
                return new RoomResource($room);
            }
        } else {
            return $this->errorResponse('user not found', 404);
        }
    }

    public function showMyRoom()
    {
        $id = auth()->user()->id;
        $myRoom = Key::all()->where('user_id', $id)->pluck('room_id');
        $findUser = DB::table('keys')->join('users', 'keys.user_id', '=', 'users.id',)
            ->whereIn('keys.room_id', $myRoom)
            ->where('keys.user_id', '!=', $id)->join('rooms', 'keys.room_id', '=', 'rooms.id')
            ->select('users.id', 'users.name', 'keys.room_id', 'rooms.type', 'rooms.name_room')->get();


        foreach ($findUser as $type) {
            if ($type->type == 'single') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'room',
                    'data' => $findUser,
                    'code' => 200,
                ], 200);
            }
        }

    }


    public
    function createRoomGroup($name)
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

    public
    function addUser(Request $request)
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

            return $this->successResponse('user add', $user->name, 200);
        } else {
            return $this->errorResponse('You are not an admin or there is a user', 400);
        }
    }
}
