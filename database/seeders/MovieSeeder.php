<?php

namespace Database\Seeders;

use App\Models\MovieModel;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Genre: ACTION
        MovieModel::factory()->createOne([
            'title' => 'The Dark Knight',
            'synopsis' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
            'director_name' => 'Christopher Nolan',
            'release_date' => '2008-07-14',
            'genre' => 'action',
            'is_public' => true,
            'cover' => null
        ]);

        MovieModel::factory()->createOne([
            'title' => 'The Matrix',
            'synopsis' => 'When a beautiful stranger leads computer hacker Neo to a forbidding underworld, he discovers the shocking truth--the life he knows is the elaborate deception of an evil cyber-intelligence.',
            'director_name' => 'Lana Wachowski',
            'release_date' => '1999-03-24',
            'genre' => 'action',
            'is_public' => false,
            'cover' => null
        ]);

        //Genre: HORROR
        MovieModel::factory()->createOne([
            'title' => 'The Conjuring',
            'synopsis' => 'Paranormal investigators Ed and Lorraine Warren work to help a family terrorized by a dark presence in their farmhouse.',
            'director_name' => 'James Wan',
            'release_date' => '2013-07-15',
            'genre' => 'horror',
            'is_public' => true,
            'cover' => null
        ]);

        MovieModel::factory()->createOne([
            'title' => 'Jason X',
            'synopsis' => "Jason Voorhees is cryogenically frozen at the beginning of the 21st century, and is discovered in the 25th century and taken to space. He gets thawed, and begins stalking and killing the crew of the spaceship that's transporting him.",
            'director_name' => 'James Isaac',
            'release_date' => '2002-04-26',
            'genre' => 'horror',
            'is_public' => false,
            'cover' => null
        ]);

        //Genre: DRAMA
        MovieModel::factory()->createOne([
            'title' => 'Forrest Gump',
            'synopsis' => "The history of the United States from the 1950s to the '70s unfolds from the perspective of an Alabama man with an IQ of 75, who yearns to be reunited with his childhood sweetheart.",
            'director_name' => 'Robert Zemeckis',
            'release_date' => '1994-06-23',
            'genre' => 'drama',
            'is_public' => true,
            'cover' => null
        ]);

        MovieModel::factory()->createOne([
            'title' => 'The Godfather',
            'synopsis' => "Don Vito Corleone, head of a mafia family, decides to hand over his empire to his youngest son Michael. However, his decision unintentionally puts the lives of his loved ones in grave danger.",
            'director_name' => 'Francis Ford Coppola',
            'release_date' => '1972-03-14',
            'genre' => 'drama',
            'is_public' => false,
            'cover' => null
        ]);

        //Genre: ROMANCE
        MovieModel::factory()->createOne([
            'title' => 'Me Before You',
            'synopsis' => "A girl in a small town forms an unlikely bond with a recently-paralyzed man she's taking care of.",
            'director_name' => 'Thea Sharrock',
            'release_date' => '2016-03-23',
            'genre' => 'romance',
            'is_public' => true,
            'cover' => null
        ]);

        MovieModel::factory()->createOne([
            'title' => 'No Hard Feelings',
            'synopsis' => "On the brink of losing her home, Maddie finds an intriguing job listing: helicopter parents looking for someone to bring their introverted 19-year-old son out of his shell before college. She has one summer to make him a man or die trying.",
            'director_name' => 'Gene Stupnitsky',
            'release_date' => '2023-06-23',
            'genre' => 'romance',
            'is_public' => false,
            'cover' => null
        ]);

        //Genre: COMEDY
        MovieModel::factory()->createOne([
            'title' => "We're the Millers",
            'synopsis' => "A veteran pot dealer creates a fake family as part of his plan to move a huge shipment of weed into the U.S. from Mexico.",
            'director_name' => 'Rawson Marshall Thurber',
            'release_date' => '2013-08-07',
            'genre' => 'comedy',
            'is_public' => true,
            'cover' => null
        ]);

        MovieModel::factory()->createOne([
            'title' => 'The Hangover',
            'synopsis' => "Three buddies wake up from a bachelor party in Las Vegas, with no memory of the previous night and the bachelor missing. They make their way around the city in order to find their friend before his wedding.",
            'director_name' => 'Todd Phillips',
            'release_date' => '2009-06-02',
            'genre' => 'comedy',
            'is_public' => false,
            'cover' => null
        ]);

        //Genre: FANTASY
        MovieModel::factory()->createOne([
            'title' => "The Chronicles of Narnia: Prince Caspian",
            'synopsis' => "The Pevensie siblings return to Narnia, where they are enlisted to once again help ward off an evil king and restore the rightful heir to the land's throne, Prince Caspian.",
            'director_name' => 'Andrew Adamson',
            'release_date' => '2008-05-16',
            'genre' => 'fantasy',
            'is_public' => true,
            'cover' => null
        ]);

        MovieModel::factory()->createOne([
            'title' => "Harry Potter and the Sorcerer's Stone",
            'synopsis' => "An orphaned boy enrolls in a school of wizardry, where he learns the truth about himself, his family and the terrible evil that haunts the magical world.",
            'director_name' => 'Chris Columbus',
            'release_date' => '2001-11-11',
            'genre' => 'fantasy',
            'is_public' => false,
            'cover' => null
        ]);
    }
}
