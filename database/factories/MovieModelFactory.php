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
            'synopsis' => fake()->words(10, true),
            'director_name' => fake()->word(),
            'genre' => MovieModel::mapGenreToModel(MovieGenre::ACTION),
            'cover' => fake()->filePath(),
            'is_public' => true,
            'release_date' => fake()->date(),
            'created_at' => fake()->dateTime()
        ];
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
