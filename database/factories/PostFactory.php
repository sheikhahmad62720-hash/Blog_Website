<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(5),
            'excerpt' => $this->faker->paragraph(2),
            'content' => $this->faker->paragraphs(8, true),
            'meta_title' => $title,
            'meta_description' => $this->faker->sentence(12),
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-6 months'),
        ];
    }
}
