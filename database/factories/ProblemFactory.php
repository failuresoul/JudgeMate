<?php

namespace Database\Factories;

use App\Models\Problem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Problem>
 */
class ProblemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);
        $title = rtrim($title, '.');
        return [
            'title'         => $title,
            'slug'          => \Illuminate\Support\Str::slug($title),
            'statement'     => $this->faker->paragraph(4) . "\n\n" . $this->faker->paragraph(3),
            'input_format'  => 'The first line contains a single integer T denoting the number of test cases.',
            'output_format' => 'For each test case, output the answer on a new line.',
            'constraints'   => '1 <= T <= 100',
            'difficulty'    => $this->faker->randomElement(['easy', 'medium', 'hard']),
            'is_published'  => true,
            'created_by'    => \App\Models\User::factory(),
        ];
    }
}
