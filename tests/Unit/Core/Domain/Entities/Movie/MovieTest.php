<?php
use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieGenre;

beforeEach(function() {
  $this->today = new DateTime();
  $this->movie = new Movie('id', 'title', 'synopsis', 'directorName', MovieGenre::ACTION, "pathToCover", true, $this->today, $this->today);
});

it("should return the right values when using getters", function () {
  expect($this->movie->id())->toBe("id");
  expect($this->movie->title())->toBe("title");
  expect($this->movie->synopsis())->toBe("synopsis");
  expect($this->movie->directorName())->toBe("directorName");
  expect($this->movie->genre())->toBe(MovieGenre::ACTION);
  expect($this->movie->cover())->toBe("pathToCover");
  expect($this->movie->isPublic())->toBe(true);
  expect($this->movie->releaseDate())->toBe($this->today);
  expect($this->movie->addedAt())->toBe($this->today);
});

it("should change movie's title", function() {
  expect($this->movie->title())->toBe("title");
  $this->movie->changeTitle("new title");
  expect($this->movie->title())->toBe("new title");
});

it("should change movie's synopsis", function() {
  expect($this->movie->synopsis())->toBe("synopsis");
  $this->movie->changeSynopsis("new synopsis");
  expect($this->movie->synopsis())->toBe("new synopsis");
});

it("should change movie's director name", function() {
  expect($this->movie->directorName())->toBe("directorName");
  $this->movie->changeDirectorName("new directorName");
  expect($this->movie->directorName())->toBe("new directorName");
});

it("should change movie's genre", function() {
  expect($this->movie->genre())->toBe(MovieGenre::ACTION);
  $this->movie->changeGenre(MovieGenre::COMEDY);
  expect($this->movie->genre())->toBe(MovieGenre::COMEDY);
});

it("should change movie's cover path", function() {
  expect($this->movie->cover())->toBe("pathToCover");
  $this->movie->changeCover("pathToNewCover");
  expect($this->movie->cover())->toBe("pathToNewCover");
});

it("should change movie's visibility", function() {
  expect($this->movie->isPublic())->toBe(true);
  $this->movie->changeVisibility(false);
  expect($this->movie->isPublic())->toBe(false);
});

it("should change movie's release date", function() {
  $yesterday = $this->today->modify('-1 day');
  expect($this->movie->releaseDate())->toBe($this->today);
  $this->movie->changeReleaseDate($yesterday);
  expect($this->movie->releaseDate())->toBe($yesterday);
});