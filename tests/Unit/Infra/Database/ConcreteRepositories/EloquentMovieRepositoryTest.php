<?php
use App\Core\Domain\Entities\Movie\FilterMovies;
use App\Core\Domain\Entities\Movie\MovieGenre;
use App\Core\Domain\Entities\Movie\OrderMovies;
use App\Core\Domain\Protocols\OrderByProps;
use App\Core\Domain\Protocols\OrderDirection;
use App\Core\Domain\Protocols\PaginationProps;
use App\Infra\Database\ConcreteRepositories\EloquentMovieRepository;
use App\Models\MovieModel;
use Database\Seeders\MovieSeeder;

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

it("should return movies with right pagination props", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 6);

  $result = $this->sut->findMany($paginationProps, null, null);

  expect(count($result->data))->toBe(6);
  expect($result->paginationProps->page)->toBe($paginationProps->page);
  expect($result->paginationProps->perPage)->toBe($paginationProps->perPage);
  expect($result->paginationProps->total)->toBe(12);
});

it("should filter movies by title", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $filterMovieProps = new FilterMovies("he Dark Knigh", null);

  $result = $this->sut->findMany($paginationProps, $filterMovieProps, null);

  expect(count($result->data))->toBe(1);
  expect($result->data[0]->title())->toBe("The Dark Knight");
});

it("should filter movies by synopsis", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $filterMovieProps = new FilterMovies("beautiful stranger leads computer hacker Neo to a forbidding underworld", null);

  $result = $this->sut->findMany($paginationProps, $filterMovieProps, null);

  expect(count($result->data))->toBe(1);
  expect($result->data[0]->title())->toBe("The Matrix");
});

it("should filter movies by genre", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $filterMovieProps = new FilterMovies("comedy", null);

  $result = $this->sut->findMany($paginationProps, $filterMovieProps, null);
  expect(count($result->data))->toBe(2);
});

it("should filter movies by director name", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $filterMovieProps = new FilterMovies("hris Colum", null);

  $result = $this->sut->findMany($paginationProps, $filterMovieProps, null);

  expect(count($result->data))->toBe(1);
  expect($result->data[0]->title())->toBe("Harry Potter and the Sorcerer's Stone");
});

it("should return only public movies", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $filterMovieProps = new FilterMovies(null, true);

  $result = $this->sut->findMany($paginationProps, $filterMovieProps, null);

  expect(count($result->data))->toBe(6);

  foreach($result->data as $movie) {
    expect($movie->isPublic())->toBeTrue();
  }
});

it("should return only private movies", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $filterMovieProps = new FilterMovies(null, false);

  $result = $this->sut->findMany($paginationProps, $filterMovieProps, null);

  expect(count($result->data))->toBe(6);

  foreach($result->data as $movie) {
    expect($movie->isPublic())->toBeFalse();
  }
});

it("should order movies by title", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $orderByProps = new OrderByProps("title", OrderDirection::ASC);
  $orderMoviesProps = new OrderMovies([$orderByProps]);

  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe("Forrest Gump");
  expect($result->data[count($result->data)-1]->title())->toBe("We're the Millers");

  $orderByProps->direction = OrderDirection::DESC;
  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe("We're the Millers");
  expect($result->data[count($result->data)-1]->title())->toBe("Forrest Gump");
});

it("should order movies by visibility", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $orderByProps = new OrderByProps("isPublic", OrderDirection::ASC);
  $orderMoviesProps = new OrderMovies([$orderByProps]);

  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  foreach($result->data as $index => $movie) {
    if($index <=5) expect($movie->isPublic())->toBeFalse();
    else expect($movie->isPublic())->toBeTrue();
  }

  $orderByProps->direction = OrderDirection::DESC;
  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  foreach($result->data as $index => $movie) {
    if($index <=5) expect($movie->isPublic())->toBeTrue();
    else expect($movie->isPublic())->toBeFalse();
  }
});

it("should order movies by release date", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $orderByProps = new OrderByProps("releaseDate", OrderDirection::ASC);
  $orderMoviesProps = new OrderMovies([$orderByProps]);

  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe("The Godfather");
  expect($result->data[count($result->data)-1]->title())->toBe("No Hard Feelings");

  $orderByProps->direction = OrderDirection::DESC;
  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe("No Hard Feelings");
  expect($result->data[count($result->data)-1]->title())->toBe("The Godfather");
});

it("should order movies by added at date", function() {
  $this->seed(MovieSeeder::class);

  $firstAddedMovie = MovieModel::where('title', "The Dark Knight")->first();
  $firstAddedMovie->created_at = "1970-10-10";
  $firstAddedMovie->save();

  $lastAddedMovie = MovieModel::where('title', "The Matrix")->first();
  $lastAddedMovie->created_at = now()->addDay();
  $lastAddedMovie->save();

  $paginationProps = new PaginationProps(1, 12);
  $orderByProps = new OrderByProps("addedAt", OrderDirection::ASC);
  $orderMoviesProps = new OrderMovies([$orderByProps]);

  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe($firstAddedMovie->title);
  expect($result->data[count($result->data)-1]->title())->toBe($lastAddedMovie->title);

  $orderByProps->direction = OrderDirection::DESC;
  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe($lastAddedMovie->title);
  expect($result->data[count($result->data)-1]->title())->toBe($firstAddedMovie->title);
});

it("should not order movies because genre is not set as an orderable field", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 12);
  $orderByProps = new OrderByProps("genre", OrderDirection::ASC);
  $orderMoviesProps = new OrderMovies([$orderByProps]);

  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe("The Dark Knight");
  expect($result->data[count($result->data)-1]->title())->toBe("Harry Potter and the Sorcerer's Stone");

  $orderByProps->direction = OrderDirection::DESC;
  $result = $this->sut->findMany($paginationProps, null, $orderMoviesProps);

  expect($result->data[0]->title())->toBe("The Dark Knight");
  expect($result->data[count($result->data)-1]->title())->toBe("Harry Potter and the Sorcerer's Stone");
});

it("should apply pagination, ordering and filtering at the same time", function() {
  $this->seed(MovieSeeder::class);
  $paginationProps = new PaginationProps(1, 3);
  $orderByProps = new OrderByProps("title", OrderDirection::ASC);
  $orderMoviesProps = new OrderMovies([$orderByProps]);
  $filterMovieProps = new FilterMovies("The C", null);

  $result = $this->sut->findMany($paginationProps, $filterMovieProps, $orderMoviesProps);

  expect(count($result->data))->toBe(3);

  expect($result->data[0]->title())->toBe("Jason X");
  expect($result->data[1]->title())->toBe("The Chronicles of Narnia: Prince Caspian");
  expect($result->data[2]->title())->toBe("The Conjuring");
});
