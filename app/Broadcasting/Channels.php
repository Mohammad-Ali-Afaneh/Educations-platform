<?php

namespace App\Broadcasting;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('private-chat.{user1}.{user2}', function ($user, $user1, $user2) {
    $userId = Session::get('user_id'); // استخدم Session كما في middleware
    return in_array($userId, [$user1, $user2]);
});