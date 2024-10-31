<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(rand(3,8));
        $status = ['draft','published'];

        return [
            'title' => $title,
            //'slug' => Str::slug($title),
            'body' => fake()->paragraphs(rand(2,5), true),
            'date' => now(),
            'status' => $status[array_rand($status, 1)],
            'author_id' => 1,
        ];
    }
}
