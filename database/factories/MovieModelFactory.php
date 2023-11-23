<?php

namespace Database\Factories;

use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Models\MovieModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MovieModelFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'synopsis' => fake()->words(),
            'director_name' => fake()->word(),
            'genre' => MovieModel::mapGenreToModel(MovieGenre::ACTION),
            'cover' => fake()->filePath(),
            'is_public' => true,
            'release_date' => fake()->dateTime()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
