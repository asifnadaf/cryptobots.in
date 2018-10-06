<?php

namespace App\Console\Commands\BittrexKoinexArbitrageBots;

use App\Classes\BittrexKoinexArbitrageBots\KoinexBittrexArbitrageOpportunitiesTrackerBot;
use Illuminate\Console\Command;
use Log;

class Command_KoinexBittrexArbitrageOpportunitiesTrackerBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:KoinexBittrexArbitrageOpportunitiesTrackerBot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records arbitrage opportunities between Koinex and Bittrex';

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
        $koinexBittrexArbitrageOpportunitiesTrackerBot = new KoinexBittrexArbitrageOpportunitiesTrackerBot();
        $koinexBittrexArbitrageOpportunitiesTrackerBot->runBot();
    }
}