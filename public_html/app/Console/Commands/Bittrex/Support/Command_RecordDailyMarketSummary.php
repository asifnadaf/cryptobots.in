<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordDailyMarketSummaryBot;
use Illuminate\Console\Command;
use Log;

class Command_RecordDailyMarketSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordDailyMarketSummary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command stores altcoins market summary every 24 hours.';

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
        $dailyMarketSummaryBot = new RecordDailyMarketSummaryBot();
        $dailyMarketSummaryBot->dailyMarketSummary();

    }
}