<?php

namespace Database\Factories\News;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News\Category;
use App\Models\User;
use Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
                'date' => strtotime(now()),
                'feature_image'=>'no-image.jpg',
                'title'=>$title=$this->faker->sentence(),
                'slug'=>Str::slug($title,'-'),
                'meta_description'=>$this->faker->sentence(3),
                'content'=>$this->faker->paragraph(3),
                'focus_keyword'=>$this->faker->word(5),
                'post_type'=>random_int(1,5),
                'status'=>random_int(1,3),
        ];
    }
}
