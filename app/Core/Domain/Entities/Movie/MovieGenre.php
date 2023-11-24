<?php

namespace App\Core\Domain\Entities\Movie;

enum MovieGenre: string {
  case HORROR = "horror";
  case DRAMA = "drama";
  case ACTION = "action";
  case ROMANCE = "romance";
  case COMEDY = "comedy";
  case FANTASY = "fantasy";
}