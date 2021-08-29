<?php

namespace App\Http\Controllers;

use App\Events\SessionEvent;
use App\Http\Resources\SessionResource;
use App\Models\Session;
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
}
