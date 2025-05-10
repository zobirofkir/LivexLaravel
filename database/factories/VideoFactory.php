<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'video_url' => $this->faker->url,
            'thumbnail' => $this->faker->imageUrl(640, 480, 'video', true),
            'description' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(30, 3600), 
            'views' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
