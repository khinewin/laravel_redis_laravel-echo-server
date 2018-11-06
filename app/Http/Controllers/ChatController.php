<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Events\ChatEvent;
use App\User;
use Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getUsers(){
        $users=User::where('id', '!=', Auth::User()->id)->get();
        return response()->json($users);
    }
    public function getMessages($receiver_id){
        $sender_id=Auth::User()->id;
        $msgs=Chat::where('sender_id', $sender_id)->where('receiver_id', $receiver_id)
            ->orWhere('sender_id', $receiver_id)->where('receiver_id', $sender_id)->get();
        return response()->json($msgs);
    }
    public function postMessages(Request $request){
        $sender_id=Auth::User()->id;
        $receiver_id=$request['receiver_id'];
        $message=$request['message'];
        $chat=new Chat();
        $chat->sender_id=$sender_id;
        $chat->receiver_id=$receiver_id;
        $chat->message=$message;
        $chat->save();

        $msg=Chat::where('id', $chat->id)->first();

        event(new ChatEvent($msg));

        return response()->json($msg);
    }
}
