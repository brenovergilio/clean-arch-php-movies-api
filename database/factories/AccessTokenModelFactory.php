<?php

namespace Database\Factories;

use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Models\AccessTokenModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Core\Domain\Helpers;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserModel>
 */
class AccessTokenModelFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = UserModel::factory()->createOne();

        return [
            'token' => strtoupper(Str::random(6)),
            'time_to_leave' => Helpers::ONE_HOUR_IN_SECONDS,
            'user_id' => $user->id,
            'intent' => AccessTokenModel::mapIntentToModel(AccessTokenIntent::CONFIRM_EMAIL),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function recoverPasswordIntent(): static
    {
        return $this->state(fn (array $attributes) => [
            'intent' => AccessTokenModel::mapIntentToModel(AccessTokenIntent::RECOVER_PASSWORD),
        ]);
    }
}
