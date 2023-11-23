<?php

namespace App\Core\Domain\Entities\Movie;

enum MovieGenre {
  case HORROR;
  case DRAMA;
  case ACTION;
  case ROMANCE;
  case COMEDY;
  case FANTASY;
}