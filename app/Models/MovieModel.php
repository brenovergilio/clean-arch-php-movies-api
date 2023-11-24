<?php

namespace App\Models;

use App\Core\Domain\Entities\Movie\Movie;
use App\Core\Domain\Entities\Movie\MovieGenre;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieModel extends Model
{
    use HasFactory;

    protected $table = "movies";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'synopsis',
        'director_name',
        'genre',
        'cover',
        'is_public',
        'release_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date'
    ];

    public function mapToDomain(): Movie {
        return new Movie(
            $this->id,
            $this->title,
            $this->synopsis,
            $this->director_name,
            MovieModel::mapGenreToDomain($this->genre),
            $this->cover,
            $this->is_public,
            $this->release_date,
            $this->created_at
        );
    }

    public static function mapGenreToDomain(string $genre): MovieGenre {
        return MovieGenre::from($genre);
    }

    public function mergeDomain(Movie $movie): void {
        $this->title = $movie->title();
        $this->synopsis = $movie->synopsis();
        $this->director_name = $movie->directorName();
        $this->genre = MovieModel::mapGenreToModel($movie->genre());
        $this->cover = $movie->cover();
        $this->is_public = $movie->isPublic();
        $this->release_date = $movie->releaseDate();
    }

    public static function mapGenreToModel(MovieGenre $genre): string {
        return $genre->value;
    }
}
