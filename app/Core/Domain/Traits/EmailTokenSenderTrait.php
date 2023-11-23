<?php

namespace App\Core\Domain\Traits;
use App\Core\Domain\Entities\User\User;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use App\Core\Domain\Helpers;
use App\Core\Domain\Traits\UniqueTokenGeneratorTrait;

trait EmailTokenSenderTrait {

  use UniqueTokenGeneratorTrait;
  
  public function handleTokenSending(User $user, AccessTokenIntent $intent): void {
    $token = $this->generateUniqueToken($user->id(), $intent, Helpers::ONE_HOUR_IN_SECONDS);
    $subject = $intent === AccessTokenIntent::CONFIRM_EMAIL ? "Confirm your email" : "Recover your password";

    $this->emailSender->sendMail($user->email(), $subject, [
      "token" => $token->getToken()
    ]);
  }
}