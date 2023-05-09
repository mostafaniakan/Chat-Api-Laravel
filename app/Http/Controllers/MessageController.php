<?php

namespace App\Http\Controllers;


use App\Http\Resources\MessageResource;
use App\Http\Resources\receivedResource;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class MessageController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user_id = auth()->user()->id;

//        validate input
        $validation = Validator::make($request->all(), [
            $request->validate([
                'messages' => 'required|string',
                'room_id' => 'required',
                'image' => 'image',
            ])
        ]);

        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(), 400);
        }
        if ($request->has('image')) {
            $imagesName = Carbon::now()->microsecond . '.' . $request->image->extension();
            Storage::disk('images')->put($imagesName, 'storage');

//    $request->image->storeAs( 'images/posts',$imagesName, 'public');
        }
//     create message
        $message = Message::create([
            'user_id' => $user_id,
            'room_id' => $request->room_id,
            'messages' => $request->messages,
            'images' => $imagesName,
        ]);

        return new MessageResource($message);

    }


    public function getMessage($room_id)
    {
//        get send message

        $message = Message::where('room_id', $room_id)->get();
        return MessageResource::collection($message);
    }


    public function update(Request $request)
    {


        $validation = Validator::make($request->all(), [
            $request->validate([
                'messages' => 'string',
                'room_id' => 'required',
                'message_id' => 'required',
                'images' => 'image'
            ])
        ]);

        $message = Message::where('id', $request->message_id)->first();

        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(), 400);
        }
        if ($request->image != null) {
            if ($request->has('image')) {
                $imagesName = Carbon::now()->microsecond . '.' . $request->image->extension();
                Storage::disk('images')->put($imagesName, 'storage');
            }
        }

        Message::where('id', $request->message_id)->where('room_id', $request->room_id)->where('user_id',auth()->user()->id)->update([
            'user_id' => $message->user_id,
            'room_id' => $message->room_id,
            'messages' => $request->messages ? $request->messages : $message->messages ,
            'images' =>  $message->images,
        ]);

    }

    public function delete(Request $request)
    {

        $validation = Validator::make($request->all(), [
            $request->validate([
                'room_id' => 'required',
                'message_id' => 'required',
            ])
        ]);


        if ($validation->fails()) {
            return $this->errorResponse($validation->messages(), 400);
        }

        Message::where('id', $request->message_id)->where('room_id', $request->room_id)->where('user_id',auth()->user()->id)->delete();

    }
}
