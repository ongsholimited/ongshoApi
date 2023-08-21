<?php

namespace Database\Factories\News;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Admin;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserHasBadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [ 
            'badge_key'=>'news',
            'user_id'=>User::all()->random()->unique()->id,
            'status'=>rand(0,1),
            'author_id'=>Admin::all()->random()->id,
        ];
    }
}