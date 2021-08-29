<?php

namespace App\Http\Controllers;

use App\Events\PrivateChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\Session;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $session = Session::find($request->session_id);
        $message = $session->messages()->create([
            'content' => $request->message
        ]);

        $chat = $message->chats()->create([
            'session_id' => $session->id,
            'user_id' => auth()->id(),
            'type' => 0
        ]);

        $message->chats()->create([
            'session_id' => $session->id,
            'user_id' => $request->receiver_id,
            'type' => 1
        ]);
        broadcast(new PrivateChatEvent($request->message, $chat));
        return response()->json(['status' => true]);

    }

    public function chats(Session $session)
    {
        return ChatResource::collection($session->chats->where('user_id', auth()->id()));
    }
}
