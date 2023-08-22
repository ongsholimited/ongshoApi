<?php

namespace Database\Factories\news;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HomeSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->word(),
            'category_id'=>Category::all()->random()->id,
            'serial'=>rand(1,10),
        ];
    }
}