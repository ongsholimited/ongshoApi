<?php

namespace Database\Factories\News;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News\Category;
use App\Models\News\Post;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostHasCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id'=> Category::all()->random()->id,
            'post_id'=> Post::all()->random()->id,
        ];
    }
}
