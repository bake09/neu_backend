<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// When TokenBased Auth is implemented
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('users.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
    // return true;
});

// TODO
Broadcast::channel('todochannel.{id}', function ($user, $id) {
    return true;
    // return (int) $user->id === (int) $id;
});

// CHAT
Broadcast::channel('chatchannel.{id}', function (User $user, $id) {
    return Chat::where('id', $id)
        ->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->exists();
});

// Chat TEST PRIVATE
// Broadcast::channel('chatchannel.{id}', function (User $user, $id) {
//     // return Chat::where('id', $id)
//     //     ->whereHas('users', function ($query) use ($user) {
//     //         $query->where('user_id', $user->id);
//     //     })
//     //     ->exists();
//     return true;
// });

// Chat TEST PRESENCE
// Broadcast::channel('presence-chatchannel.{id}', function (User $user, $id) {
//     if (Chat::where('id', $id)
//         ->whereHas('users', function ($query) use ($user) {
//             $query->where('id', $user->id);
//         })
//         ->exists()) {
//         return ['id' => $user->id, 'name' => $user->name]; // Präsenz-Kanäle erfordern eine Rückgabe von Benutzerdaten
//     }
//     return false;
// });
Broadcast::channel('presence-chatchannel.{id}', function (User $user, $id) {
    // Check if the chat with the given id exists and includes the current user
    $chatExists = Chat::where('id', $id)
        ->whereHas('users', function ($query) use ($user) {
            $query->where('id', $user->id);
        })
        ->exists();

    if ($chatExists) {
        // Return user information if the user is part of the chat
        return ['id' => $user->id, 'name' => $user->name]; // Präsenz-Kanäle erfordern eine Rückgabe von Benutzerdaten
    }

    // Return false if the user is not part of the chat
    return false;
});