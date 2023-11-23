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
        'release_date' => 'datetime'
    ];

    public function mapToDomain(): Movie {
        return new Movie(
            $this->id,
            $this->title,
            $this->synopsis,
            $this->director_name,
            $this->mapGenreToDomain(),
            $this->cover,
            $this->is_public,
            $this->release_date,
            $this->created_at
        );
    }

    private function mapGenreToDomain(): MovieGenre {
        switch($this->genre) {
            case 'horror':
                return MovieGenre::HORROR;
            case 'drama':
                return MovieGenre::DRAMA;
            case 'action':
                return MovieGenre::ACTION;
            case 'romance':
                return MovieGenre::ROMANCE;
            case 'comedy':
                return MovieGenre::COMEDY;
            default:
                return MovieGenre::FANTASY;
        }
    }
}
