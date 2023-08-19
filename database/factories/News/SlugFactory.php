<?php

namespace Database\Factories\News;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News\Post;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SlugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'slug_name'=>Post::all()->random()->slug,
            'slug_type'=>'post',
        ];
    }
}
