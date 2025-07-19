<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => User::all()->random()->id,
            // 'user_id' => User::first()-id, // // this is take gest the first id user 
            'user_id' => User::inRandomOrder()->first()->id,
            // 'title' => fake()->title(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement(['high', 'medium', 'low']),
        ];
    }
}

// =====
//  return [
//             'user_id' => User::all()->random()->id,
//             'title' => fake()->title(),
//             'descriotion' => fake()->paragraph(),
//             'priority' => fake()->randomElement(['high', 'medium', 'low']),
//         ];
// =====
//  return [
//             // 'user_id' => User::first()-id, // // this is take gest the first id user 
//             // 'user_id' => User::inRandomOrder()->first()->id,
//             'user_id' => User::inRandomOrder()->first()->id,
//             'title' => fake()->sentence(),
//             'descriotion' => fake()->paragraph(),
//             'priority' => fake()->randomElement(['high', 'medium', 'low']),
//         ];
