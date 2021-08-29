<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('online', function ($user) {
    return $user;
});

Broadcast::channel('chat', function ($user) {
    return $user;
});

Broadcast::channel('chat.{session_id}', function ($user, $session_id) {

    $session = \App\Models\Session::find((int)$session_id);
    if ((int)$user->id === (int)$session->user1_id || (int)$user->id === (int)$session->user2_id) {
        return true;
    }
    return false;
});
