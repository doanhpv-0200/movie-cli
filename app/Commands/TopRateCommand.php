<?php

namespace App\Commands;

use App\Commands\Helper\MovieHelper;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class TopRateCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'top-rated';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Top rated movies';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        [$headers, $rows] = MovieHelper::getMovieList(MovieHelper::TOPRATE);
        $this->info("Top rated movies");
        $this->table($headers, $rows);
    }

    /**
	 * Define the command's schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	public function schedule(Schedule $schedule): void
	{
		// $schedule->command(static::class)->everyMinute();
	}
}
