<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $message;
    public $sendMsgTo;
    public $msg_from;
    public $path;
    public $name;

   
    public function __construct($sendMsgTo,$msg_from,$message,$path,$name)
    {
       
        $this->sendMsgTo = $sendMsgTo;
        $this->msg_from = $msg_from;
        $this->message = $message;
        $this->path = $path;
        $this->name=$name;

        $this->dontBroadcastToCurrentUser();
     
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat.'.$this->path);
    }
}
