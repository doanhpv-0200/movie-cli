<?php
/**
 * Created by PhpStorm.
 * User: doanh
 * Date: 22/02/2018
 * Time: 12:49
 */

namespace App\Commands\Helper;

use Symfony\Component\Console\Helper\TableSeparator;
use Tmdb\ApiToken;
use Tmdb\Client;
use Tmdb\Model\Movie;
use Tmdb\Repository\MovieRepository;

class MovieHelper
{
    const POPULAR = "popular";
    const TOPRATE = "top-rate";
    const UPCOMING = "upcoming";
    const PLAYING = "playing";

    /**
     * @param string $type
     * @return array
     */
    public static function getMovieList(string $type): array {
        $token = new ApiToken(config('tmdb.api_key'));
        $client = new Client($token);
        $movieRepository = new MovieRepository($client);

        $movies = [];
        switch ($type) {
            case self::POPULAR:
                $movies = $movieRepository->getPopular();
                break;
            case self::TOPRATE:
                $movies = $movieRepository->getTopRated();
                break;
            case self::UPCOMING:
                $movies = $movieRepository->getUpcoming();
                break;
            case self::PLAYING:
                $movies = $movieRepository->getNowPlaying();
                break;
        }

        return self::getMovieListTable($movies);
    }

    /**
     * @param int $movieId
     * @return array
     */
    public static function getMovieInformation(int $movieId): array {
        $token = new ApiToken(config('tmdb.api_key'));
        $client = new Client($token);

        /** @var array $movie **/
        $movie = $client->getMoviesApi()->getMovie($movieId);

        /** @var array $credits */
        $credits = $client->getMoviesApi()->getCredits($movieId);
        $casts = self::extractCast($credits['cast']);

        $rows = [
            ["Title", $movie['title']],
            new TableSeparator(),
            ["Genres", self::extractGenres($movie['genres'])],
            new TableSeparator(),
            ["Budget", "\${$movie['budget']}"],
            new TableSeparator(),
            ["Revenue", "\${$movie['revenue']}"],
            new TableSeparator(),
            ["Release date", $movie['release_date']],
            new TableSeparator(),
            ["Runtime", "{$movie['runtime']} minutes"],
            new TableSeparator(),
            ["Homepage", $movie['homepage']],
            new TableSeparator(),
            ["Cast", $casts],
            new TableSeparator(),
            ["Overview", wordwrap($movie['overview'], 100, "\n")],
        ];

        return $rows;
    }

    /**
     * @param array $casts
     * @return string
     */
    private static function extractCast(array $casts): string {
        $persons = [];
        foreach ($casts as $people) {
            if ((int)$people['order'] < 10) {
                $persons[] = "{$people['name']}: {$people['character']}";
            }
        }

        return implode("\n", $persons);
    }

    /**
     * @param array $genres
     * @return string
     */
    private static function extractGenres(array $genres) : string {
        $result = collect($genres)->pluck("name")->toArray();

        return implode(", ", $result);
    }

    /**
     * @param Movie[] $movies
     * @return array
     */
    private static function getMovieListTable($movies): array {
        $header = ['#', 'ID', 'Title', 'Vote / Total', 'Overview'];
        $rows = [];
        $count = 1;

        /** @var Movie $movie **/
        foreach ($movies as $movie) {
            $rows[] = array(
                '#' => $count,
                'ID' => $movie->getId(),
                'Title' => $movie->getTitle(),
                'Vote' => "{$movie->getVoteAverage()} / {$movie->getVoteCount()}",
                'Overview' => str_limit($movie->getOverview(), 100),
            );

            $count++;
        }

        return [$header, $rows];
    }
}