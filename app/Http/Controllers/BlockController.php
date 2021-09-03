<?php

namespace App\Http\Controllers;

use App\Events\BlockedEvent;
use App\Models\Session;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function block(Session $session)
    {
        $session->block();
        broadcast(new BlockedEvent($session->id, true));
        return response('blocked', 200);
    }

    public function unblock(Session $session)
    {
        $session->unblock();
        broadcast(new BlockedEvent($session->id, false));
        return response('unblocked', 200);
    }
}
