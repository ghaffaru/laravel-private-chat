<?php

namespace App\Http\Controllers;

use App\Events\MessageReadEvent;
use App\Events\SessionEvent;
use App\Http\Resources\ChatResource;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create(Request $request)
    {
        $sessionExist = Session::where(['user1_id' => auth()->id(), 'user2_id' => $request->friend_id])
                                ->orWhere(['user2_id' => $request->friend_id, 'user1_id' => auth()->id()])->first();

        if (!$sessionExist) {
            $session = Session::create(['user1_id' => auth()->id(), 'user2_id' => $request->friend_id]);
            broadcast(new SessionEvent(new SessionResource($session), auth()->id()));
            return new SessionResource($session);
        }

    }

    public function read(Session $session)
    {
        $chats = $session->chats->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth()->id());

        foreach ($chats as $chat)
        {
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new MessageReadEvent(new ChatResource($chat)));
        }
    }
}
