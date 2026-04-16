<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    public function index()
    {
        if (!Session::has('user_id') || !in_array(Session::get('user_type'), ['student', 'teacher'])) {
            return redirect()->route('login')->with('error', 'You must log in first');
        }

        $messages = ChatMessage::latest()->take(50)->get()->reverse();

        $userName = Session::get('user_name');
        $userType = Session::get('user_type');
        $userId = Session::get('user_id');

        return view('chat', compact('messages', 'userName', 'userType', 'userId'));
    }

    public function store(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        if (!Session::has('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $message = ChatMessage::create([
            'user_id' => Session::get('user_id'),
            'user_type' => Session::get('user_type'),
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id' => $message->id,
            'user_id' => $message->user_id,
            'user_type' => $message->user_type,
            'user_name' => $message->user_name,
            'message' => $message->message,
            'created_at' => $message->created_at->format('H:i'),
        ]);
    }
}