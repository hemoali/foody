<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FoursquareApiFetcher;

class FetchFourSquareAPICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:foursquareapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches restaurants from foursquare API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $foursquareApiFetcher = new FoursquareApiFetcher();
        $foursquareApiFetcher->fetchData();
        \Log::info('Data pulled from Foursquare API');
    }
}
