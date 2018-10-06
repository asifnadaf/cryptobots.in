<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\SellOnResistancePriceBot;
use Illuminate\Console\Command;
use App\Models\BotSettingsModel;

class Command_SellOnResistancePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:SellOnResistancePrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sells altcoins for all clients when resistance price is crossed';

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
        $pauseTrading = $settings->pauseSellOnResistancePriceBot;
        if (strcasecmp($pauseTrading, 'Yes') == 0) {
            return;
        }

        $sellOnResistancePriceBot = new SellOnResistancePriceBot();
        $sellOnResistancePriceBot->sellAboveResistancePrice();

    }
}