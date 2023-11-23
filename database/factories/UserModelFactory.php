<?php

namespace Database\Factories;

use App\Core\Domain\Entities\User\Role;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserModelFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'cpf' => (string) fake()->unique()->randomNumber(11),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => UserModel::mapRoleToModel(Role::ADMIN),
            'photo' => null,
            'email_confirmed' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'email_confirmed' => false
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function withPhoto(): static
    {
        return $this->state(fn (array $attributes) => [
            'photo' => fake()->filePath()
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserModel::mapRoleToModel(Role::CLIENT),
        ]);
    }
}
