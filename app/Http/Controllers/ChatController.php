<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Events\ChatEvent;




use Illuminate\Http\Request;

class ChatController extends Controller
{

    function getName($email){
       
      $name=  DB::table('users')
        ->where('email',$email)
        ->select('name')
        ->get();
        return $name[0]->name;
    }
    function getPusherPath(Request $request)
    {
        $email = $request->email;

        return DB::table('pusher_path')
            ->where('pusher_path.email', $email)   
            ->join('users','users.email','pusher_path.email')
            ->select('users.email','pusher_path.path','users.name')
            ->get();
    }

    function sendMessages(Request $request)
    {
        $sendMsgTo = $request->msg_to;
        $msg_from = $request->msg_from;
        $message = $request->message;
        $name=$request->name;
        $path = "";

      

            $pathzz =  DB::table('pusher_path')
            ->where('email', $sendMsgTo)
            ->get();
            
        

        foreach ($pathzz as $pathz) {
            $path = $pathz->path;
        }

        


       //event(new ChatEvent("hjello", "wadfsd", "sfsf","event"));


        event(new ChatEvent($sendMsgTo, $msg_from, $message, $path,$name));
        // event(new ChatEvent('hello','waaa','jack'));

       

        return DB::table('messages')
            ->insert(
                [
                    'msg_from' => $msg_from,
                    'msg_to' => $sendMsgTo,
                    'message' => $message
                ]
            );
    }
    function sendMessage(Request $request)
    {
    }

    function list_users()
    {
       
        return DB::table('users')
            ->get();
    }


    function getMessages(Request $request)
    {

        $email = $request->email;
        $sendMsgTo = $request->sendMsgTo;



        return DB::table('messages')
            ->where([
                ['msg_from', '=', $email],
                ['msg_to', '=', $sendMsgTo]
            ])
            ->orWhere([
                ['msg_from', '=', $sendMsgTo],
                ['msg_to', '=', $email]
            ])
            ->join('users','users.email','messages.msg_from')
            ->select('msg_from','msg_to','message','name')
            ->get();
    }
}
