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
        $nbOfIter = 100;
        $this->call(UserSeeder::class);
        Category::factory(5)->create();


        \App\Models\User::factory()
            ->count(10)
            ->create();
        
        for($i=0;$i<=$nbOfIter;$i++){
            \App\Models\Post::factory()
            ->has(Comment::factory()->count(rand(50,80)))
            ->has(PostLike::factory()->count(rand(20,200)))
            ->create();
        }
        }
}
