<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatsListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Bestimmen des Namens
        $name = $this->is_group ? $this->name : $this->getChatPartnerName($request->user());

        return [
            'id' => $this->id,
            'name' => $name,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'isGroup' => $this->is_group,
            'isTyping' => NULL,
            'newMessage' => '',
            'last_message' => $this->whenLoaded('lastMessage', function() {
                return [
                    'read' => false,
                    'chat_id' => $this->lastMessage->chat_id,
                    'content' => $this->lastMessage->content,
                    'created_at' => $this->lastMessage->created_at,
                    'created_at_human' => Carbon::parse($this->lastMessage->created_at)->diffForHumans(),
                    'user' => new UserResource($this->lastMessage->user)
                ];
            }),
            'unreadMessages' => []
        ];
    }

    /**
     * Bestimmen des Namens des Gesprächspartners.
     */
    private function getChatPartnerName($currentUser)
    {
        // Holen aller Benutzer außer dem aktuellen Benutzer
        $chatPartner = $this->users->where('id', '!=', $currentUser->id)->first();
        return $chatPartner ? $chatPartner->name : 'Unknown';
    }
}
