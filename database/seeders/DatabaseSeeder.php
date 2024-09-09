<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if((User::where('email', 'test@example.com')->exists())==false){
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => '1234'
            ]);
        }

        Event::factory(3)->create();
    }
}
