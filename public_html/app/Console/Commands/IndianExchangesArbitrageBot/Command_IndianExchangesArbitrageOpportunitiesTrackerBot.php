<?php

namespace App\Console\Commands\BittrexKoinexArbitrageBots;

use App\Classes\IndianExchangesArbitrageBot\IndianExchangesArbitrageBot;
use Illuminate\Console\Command;
use Log;

class Command_IndianExchangesArbitrageOpportunitiesTrackerBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:Command_IndianExchangesArbitrageOpportunitiesTrackerBot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records arbitrage opportunities in selected Indian exchanges';

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
        $indianExchangesArbitrageBot = new IndianExchangesArbitrageBot();
        $indianExchangesArbitrageBot->runBot();
    }
}