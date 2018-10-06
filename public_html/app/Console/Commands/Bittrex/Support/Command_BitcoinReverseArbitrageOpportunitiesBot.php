<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\BitcoinReverseArbitrageOpportunitiesBot;
use Illuminate\Console\Command;
use Log;

class Command_BitcoinReverseArbitrageOpportunitiesBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:BitcoinReverseArbitrageOpportunitiesBot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records bitcoin arbitrage opportunities in international market.';

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
        $bitcoinReverseArbitrageOpportunitiesBot = new BitcoinReverseArbitrageOpportunitiesBot();
        $bitcoinReverseArbitrageOpportunitiesBot->bitcoinReverseArbitrageOpportunitiesBot();

    }
}