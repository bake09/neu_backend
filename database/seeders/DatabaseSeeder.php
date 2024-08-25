<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Task;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Erstelle Benutzer
        $user1 = User::factory()->create([
            'name' => 'A User',
            'email' => 'test@test.com',
        ]);
        $user2 = User::factory()->create([
            'name' => 'B User',
            'email' => 'test2@test.com',
        ]);
        $user3 = User::factory()->create([
            'name' => 'C User',
            'email' => 'test3@test.com',
        ]);

        // Erstelle Aufgaben
        Task::factory()->create([
            'content' => 'Message 1',
            'user_id' => $user1->id,
        ]);
        Task::factory()->create([
            'content' => 'Message 2',
            'user_id' => $user2->id,
        ]);
        Task::factory()->create([
            'content' => 'Message 3',
            'user_id' => $user3->id,
        ]);

        // Erstelle Chats
        $chat1 = Chat::factory()->create([
            'name' => 'TestROOM 1',
            'is_group' => true,
        ]);
        $chat2 = Chat::factory()->create([
            'name' => 'TestROOM 2',
        ]);
        $chat3 = Chat::factory()->create([
            'name' => 'TestROOM 3',
        ]);

        // Füge Benutzer den Chats hinzu (Pivot-Tabelle `chat_user`)
        $chat1->users()->attach([$user1->id, $user2->id, $user3->id]);
        $chat2->users()->attach([$user1->id, $user2->id]);
        $chat3->users()->attach([$user2->id, $user3->id]);

        // Erstelle Nachrichten für die Chats
        Message::factory()->create([
            'chat_id' => $chat1->id,
            'content' => 'Message 1',
            'image_url' => null,
            'user_id' => $user1->id,
        ]);
        Message::factory()->create([
            'chat_id' => $chat1->id,
            'content' => 'Message 2',
            'image_url' => null,
            'user_id' => $user2->id,
        ]);
        Message::factory()->create([
            'chat_id' => $chat1->id,
            'content' => 'Message 3',
            'image_url' => null,
            'user_id' => $user3->id,
        ]);

        Message::factory()->create([
            'chat_id' => $chat2->id,
            'content' => 'Message 1',
            'image_url' => null,
            'user_id' => $user1->id,
        ]);
        Message::factory()->create([
            'chat_id' => $chat2->id,
            'content' => 'Message 2',
            'image_url' => null,
            'user_id' => $user2->id,
        ]);
        Message::factory()->create([
            'chat_id' => $chat2->id,
            'content' => 'Message 3',
            'image_url' => null,
            'user_id' => $user2->id,
        ]);

        Message::factory()->create([
            'chat_id' => $chat3->id,
            'content' => 'Message 1',
            'image_url' => null,
            'user_id' => $user3->id,
        ]);
        Message::factory()->create([
            'chat_id' => $chat3->id,
            'content' => 'Message 2',
            'image_url' => null,
            'user_id' => $user3->id,
        ]);
        Message::factory()->create([
            'chat_id' => $chat3->id,
            'content' => 'Message 3',
            'image_url' => null,
            'user_id' => $user2->id,
        ]);
    }
}
