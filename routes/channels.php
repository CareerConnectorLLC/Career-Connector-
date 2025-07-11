<?php

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('career-connector', function (User $user) {
    // The `User` type-hint ensures only authenticated users can join.
    return [
        'id' => $user->id,
        'name' => $user->name,
        'role' => $user->roles->first()?->name,
    ];
});

Broadcast::channel('private.conversation.{conversationId}', function (User $user, int $conversationId) {
    $conversation = Conversation::find($conversationId);
    if (!$conversation) {
        return false;
    }
    
    return $user->id === $conversation->customer_id || $user->id === $conversation->provider_id;
});
