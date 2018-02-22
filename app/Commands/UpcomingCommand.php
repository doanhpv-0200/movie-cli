<?php

namespace App\Commands;

use App\Commands\Helper\MovieHelper;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class UpcomingCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'upcoming';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Upcoming movie';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        [$headers, $rows] = MovieHelper::getMovieList(MovieHelper::UPCOMING);
        $this->info("Upcoming movies");
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
