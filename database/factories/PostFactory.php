<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::where('role', 'moderator')->pluck('id')->toArray();
        $cat = Category::pluck('id')->toArray();

        return [
            'title' => fake()->text(64),
            'description' => fake()->text(200),
            'content' => fake()->paragraph(),
            'image' => fake()->imageUrl(),
            'user_id' => fake()->randomElement($users),
            'category_id' => fake()->randomElement($cat),
        ];
    }
}
