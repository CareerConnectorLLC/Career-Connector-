<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(protected Message $message)
    {
        //
    }

    public function broadcastWith(): array
    {
        // The message model will be serialized to JSON.
        // The 'sender' relationship is already loaded in the controller.
        return ['message' => $this->message];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // The message is always sent on the conversation channel.
        $channels = [
            new PrivateChannel('private.conversation.' . $this->message->conversation_id),
        ];

        // If there's a recipient, also broadcast on their user-specific channel.
        // This is used for notifications and unread counts when the user is not in the active conversation.
        // The receiver_id is set in ChatMessageController.
        if ($this->message->receiver_id) {
            $channels[] = new PrivateChannel('private.user.' . $this->message->receiver_id);
        }

        return $channels;
    }
}
