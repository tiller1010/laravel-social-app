<?php

namespace App\Http\Controllers\Api;

use App\Message;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function index(Message $message)
    {
        return Message::get();
    }

    public function store(Message $message)
    {
        return Message::create([
            'From_user_id' => auth()->user()->id,
            'message' => request('message'),
        ]);
    }
}