<?php

namespace Database\Seeders;

use App\Infra\Handlers\BcryptHandler;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function __construct(
        private BcryptHandler $bcryptHandler
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserModel::factory()->createOne([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => $this->bcryptHandler->generate('password')
        ]);

        UserModel::factory()->client()->createOne([
            'name' => 'Verified User',
            'email' => 'verified@mail.com',
            'password' => $this->bcryptHandler->generate('password')
        ]);

        UserModel::factory()->client()->unverified()->createOne([
            'name' => 'Unverified User',
            'email' => 'unverified@mail.com',
            'password' => $this->bcryptHandler->generate('password')
        ]);
    }
}
