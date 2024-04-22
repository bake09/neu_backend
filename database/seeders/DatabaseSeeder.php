<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Task::factory(10)->create();

        User::factory()->create([
            'name' => 'User 1',
            'email' => 'test@test.com',
        ]);
        
        User::factory()->create([
            'name' => 'User 2',
            'email' => 'test2@test.com',
        ]);
    }
}
