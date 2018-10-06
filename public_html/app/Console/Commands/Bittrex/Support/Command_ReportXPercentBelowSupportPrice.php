<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\ReportXPercentBelowSupportPriceBot;
use Illuminate\Console\Command;
use Log;

class Command_ReportXPercentBelowSupportPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:ReportXPercentBelowSupportPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends an email whenever an altcoin price falls below support.';

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
        $xPercentBelowSupportPriceAfterBuy = new ReportXPercentBelowSupportPriceBot();
        $xPercentBelowSupportPriceAfterBuy->XPercentBelowSupportPrice();

    }
}