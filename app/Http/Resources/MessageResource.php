<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'chat_id' => $this->chat_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'created_at_human' => Carbon::parse($this->created_at)->diffForHumans(),
            'created_at_time' => Carbon::parse($this->created_at)->format('H:i'),
            'read' => false,
            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}
