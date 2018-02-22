<?php

namespace App\Commands;

use App\Commands\Helper\MovieHelper;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class InfoCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'info {id? : Movie\'s ID}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get movie\'s information';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $movieId = $this->argument("id");

        while (empty($movieId)) {
            $movieId = $this->ask("Please enter movie ID");
        }

        $rows = MovieHelper::getMovieInformation($movieId);
        $this->table([], $rows);
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
