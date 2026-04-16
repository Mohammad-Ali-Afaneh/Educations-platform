<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('private-chat.{user1}.{user2}', function ($user, $user1, $user2) {
    $userId = Auth::id() ?? Session::get('user_id'); // دعم لكلا النظامين للأمان
    return in_array($userId, [$user1, $user2]);
});