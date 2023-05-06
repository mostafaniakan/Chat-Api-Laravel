<?php

namespace App\Http\Controllers;


use App\Http\Resources\MessageResource;
use App\Http\Resources\receivedResource;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->user()->id;

//        validate input
        $validation = Validator::make($request->all(), [
            $request->validate([
                'messages' => 'required|string',
                'room_id' => 'required',
            ])
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(), 400);
        }

//     create message
        $message = Message::create([
            'user_id' => $user_id,
            'room_id' => $request->room_id,
            'messages' => $request->messages,
        ]);
        return new MessageResource($message);

    }


    public function getMessage($room_id)
    {
//        get send message

        $message = Message::where('room_id', $room_id)->get();
         return MessageResource::collection($message);
    }


//    public function receivedMessages()
//    {
//
//        $getMessage = Room::where('second_user_id', auth()->user()->id)->get('id');
//
//        if (!$getMessage->isEmpty()) {
//            $receivedMessage = Message::where('room_id', '=', $getMessage[0]['id'])->get();
//            return receivedResource::collection($receivedMessage);
//        } else {
//            return response()->json('empty');
//        }
//    }
}