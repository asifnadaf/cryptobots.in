<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\ReportAboveResistancePriceBot;
use Illuminate\Console\Command;
use Log;

class Command_ReportAboveResistancePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:ReportAboveResistancePrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends an email if the altcoin crosses resistance price.';

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
        $aboveResistancePriceAfterBuyBot = new ReportAboveResistancePriceBot();
        $aboveResistancePriceAfterBuyBot->reportAboveResistancePrice();

    }
}