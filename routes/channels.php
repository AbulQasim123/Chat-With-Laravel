<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat-channel.{senderId}', function ($user, $senderId) {
    // Auth logic: user can only listen to their own channel
    return (int) $user->id === (int) $senderId;
});
