<?php

namespace App\Core\Domain\Entities\User;

interface UserRepository {
  public function create(User $user, bool $returning = false): ?User;
  public function update(User $user, bool $returning = false): ?User;
  public function findByID(string|int $id): ?User;
  public function findByEmail(string $email): ?User;
  public function findByCPF(string $email): ?User;
  public function delete(string|int $id): void;
}