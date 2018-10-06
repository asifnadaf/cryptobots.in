<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\UpdateSellLimitOrderBookToXTimesBot;
use Illuminate\Console\Command;
use App\Models\BotSettingsModel;

class Command_UpdateSellLimitOrderBookToXTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:UpdateSellLimitOrderBookToXTimes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates sell price of all altcoins for all clients to pump factors times from setting';

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
        $pauseTrading = $settings->pauseUpdateSellLimitOrderBookToXTimesBot;
        if (strcasecmp($pauseTrading, 'Yes') == 0) {
            return;
        }

        $updateSellLimitOrderBookToXTimesBot = new UpdateSellLimitOrderBookToXTimesBot();
        $updateSellLimitOrderBookToXTimesBot->updateSellLimitOrderBookToAutoXTime();

    }
}