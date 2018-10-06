<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\BuyAtSupportBot;
use Illuminate\Console\Command;
use App\Models\BotSettingsModel;
use Log;

class Command_BuyAtSupport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:BuyAtSupport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command buys altcoin on bittrex when support price is reached.';

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

        $settings = BotSettingsModel::first();
        $pauseTrading = $settings->pauseBuyAtSupportBot;
        if (strcasecmp($pauseTrading, 'Yes') == 0) {
            return;
        }

        $bittrexSupportResistanceBot = new BuyAtSupportBot();
        $bittrexSupportResistanceBot->buyAtSupportPriceBot();

    }
}