<?php

namespace App\Http\Controllers;

use App\Events\Chat\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Resources\ChatResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ChatsListResource;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $chats = Chat::whereHas('users', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['users', 'lastMessage'])->get();

        return ChatsListResource::collection($chats);
    }

    public function store(Request $request)
    {
        $user_id = Auth::id();

        // Validierung der Eingabe
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'content' => 'required|max:255',
            'image_url' => 'nullable|url', // Falls du ein Bild-URL-Feld hinzufügen möchtest
        ]);

        // Überprüfen, ob der Benutzer Mitglied des Chats ist
        $isParticipant = Chat::where('id', $request->chat_id)
            ->whereHas('users', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'status' => 'failure',
                'message' => 'User is not a participant in this chat.'
            ], 403); // HTTP-Status 403 für "Forbidden"
        }

        // Nachricht erstellen
        $message = Message::create([
            'chat_id' => $request->chat_id,
            'content' => $request->content,
            'user_id' => $user_id,
            'image_url' => $request->image_url, // Falls vorhanden
        ]);
        
        $message->load('user');
        
        broadcast(new MessageSent(new MessageResource($message), $message->chat_id))->toOthers();

        return response()->json([
            'status' => 'Message created successfully.',
            'message' => new MessageResource($message) // Nutze eine Ressource falls vorhanden
        ], 201);
    }

    public function show(Chat $chat, Request $request)
    {
        // return new ChatResource($chat->load(['messages.user']));


        // // Paginieren Sie die Nachrichten in der Beziehung
        // $messages = $chat->messages()->with('user')->paginate(3);

        // // Fügen Sie die paginierten Nachrichten zur Chat-Ressource hinzu
        // return (new ChatResource($chat))->additional(['messages' => $messages]);

        // Paginieren Sie die Nachrichten in der Beziehung
        // $messages = $chat->messages()->with('user')->paginate(3);
        $messages = $chat->messages()->with('user')->get();

        // Transformieren Sie die paginierten Nachrichten mit MessageResource
        $paginatedMessages = MessageResource::collection($messages);

        // Fügen Sie die Chat-Daten und die paginierten Nachrichten zusammen und geben sie zurück
        return (new ChatResource($chat))
            ->additional([
                'messages' => [
                    'data' => $paginatedMessages,
                    // 'current_page' => $messages->currentPage(),
                    // 'first_page_url' => $messages->url(1),
                    // 'from' => $messages->firstItem(),
                    // 'last_page' => $messages->lastPage(),
                    // 'last_page_url' => $messages->url($messages->lastPage()),
                    // 'links' => $messages->linkCollection()->toArray(),
                    // 'next_page_url' => $messages->nextPageUrl(),
                    // 'path' => $messages->path(),
                    // 'per_page' => $messages->perPage(),
                    // 'prev_page_url' => $messages->previousPageUrl(),
                    // 'to' => $messages->lastItem(),
                    // 'total' => $messages->total(),
                ]
            ]);
            // ->additional(['messages' => $paginatedMessages])
            // ->additional(['meta' => [
            //     'current_page' => $messages->currentPage(),
            //     'first_page_url' => $messages->url(1),
            //     'from' => $messages->firstItem(),
            //     'last_page' => $messages->lastPage(),
            //     'last_page_url' => $messages->url($messages->lastPage()),
            //     'links' => $messages->linkCollection()->toArray(),
            //     'next_page_url' => $messages->nextPageUrl(),
            //     'path' => $messages->path(),
            //     'per_page' => $messages->perPage(),
            //     'prev_page_url' => $messages->previousPageUrl(),
            //     'to' => $messages->lastItem(),
            //     'total' => $messages->total(),
            // ]]);
    }

    public function update(Request $request, Chat $chat)
    {
        //
    }

    public function destroy(Chat $chat)
    {
        //
    }
}