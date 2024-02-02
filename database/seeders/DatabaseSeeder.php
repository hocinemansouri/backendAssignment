<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        Category::factory(5)->create();


        \App\Models\User::factory()
            ->count(10)
            ->create();

        \App\Models\Post::factory()
            ->count(100)
            ->hasComments(50)
            ->create();
    }
}
