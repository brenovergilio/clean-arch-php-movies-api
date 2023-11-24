<?php
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Infra\Database\ConcreteRepositories\EloquentMovieRepository;
use App\Models\MovieModel;

beforeEach(function() {
  $this->sut = new EloquentMovieRepository();
});

it("should return null when calling findByID because movie does not exist", function() {
  expect($this->sut->findByID('id'))->toBe(null);
});

it("should return movie when calling findByID because movie does exist", function() {
  $movie = MovieModel::factory()->createOne();
  $result = $this->sut->findByID($movie->id);

  expect($movie->id)->toBe($result->id());
  expect($movie->title)->toBe($result->title());
  expect($movie->synopsis)->toBe($result->synopsis());
  expect($movie->director_name)->toBe($result->directorName());
  expect($movie->genre)->toBe(MovieModel::mapGenreToModel($result->genre()));
  expect($movie->cover)->toBe($result->cover());
  expect($movie->is_public)->toBe($result->isPublic());
});

it("should delete a movie", function() {
  $movie = MovieModel::factory()->createOne();
  expect(MovieModel::find($movie->id))->not->toBeNull();

  $this->sut->delete($movie->id);
  expect(MovieModel::find($movie->id))->toBeNull();
});

it("should update movie and return it", function() {
  $movie = MovieModel::factory()->createOne()->mapToDomain();

  $movie->changeTitle("Another Title");
  $movie->changeSynopsis("Another Synopsis");
  $movie->changeDirectorName("Another Director");
  $movie->changeGenre(MovieGenre::COMEDY);
  $movie->changeCover("anotherCover");
  $movie->changeVisibility(false);

  $result = $this->sut->update($movie, true);

  expect($movie->id())->toBe($result->id());
  expect($result->title())->toBe("Another Title");
  expect($result->synopsis())->toBe("Another Synopsis");
  expect($result->directorName())->toBe("Another Director");
  expect($result->genre())->toBe(MovieGenre::COMEDY);
  expect($result->cover())->toBe("anotherCover");
  expect($result->isPublic())->toBe(false);
});

it("should create movie and return it", function() {
  $movie = MovieModel::factory()->createOne([
    "title" => "Title",
    "synopsis" => "Synopsis",
    "director_name" => "Director",
    "genre" => MovieModel::mapGenreToModel(MovieGenre::COMEDY),
    "cover" => "Cover",
    "is_public" => false
  ])->mapToDomain();

  $result = $this->sut->create($movie, true);

  expect($result->title())->toBe("Title");
  expect($result->synopsis())->toBe("Synopsis");
  expect($result->directorName())->toBe("Director");
  expect($result->genre())->toBe(MovieGenre::COMEDY);
  expect($result->cover())->toBe("Cover");
  expect($result->isPublic())->toBe(false);
});
