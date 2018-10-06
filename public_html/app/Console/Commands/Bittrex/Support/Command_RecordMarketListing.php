<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordMarketListingBot;
use Illuminate\Console\Command;
use Log;

class Command_RecordMarketListing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordMarketListing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records whenever a new market is being listed';

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
        $reportMarketDelistingBot = new RecordMarketListingBot();
        $reportMarketDelistingBot->recordMarketListing();

    }
}