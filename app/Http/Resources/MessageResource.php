<?php

namespace App\Http\Resources;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => auth()->user()->name,
            'messages' => $this->messages,
            'room_id' => $this->room_id,
            'image'=>$this->images,
        ];
    }
}
