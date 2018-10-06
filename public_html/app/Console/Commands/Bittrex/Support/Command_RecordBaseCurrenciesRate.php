<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordBaseCurrenciesRateBot;
use App\Classes\BittrexBots\Support\ResetIsInvestedFlagBot;
use Illuminate\Console\Command;
use Log;

class Command_RecordBaseCurrenciesRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordBaseCurrenciesRate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command gets base currencies rate and stores it in database';

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
        $pumpAndDumpReportBot = new RecordBaseCurrenciesRateBot();
        $pumpAndDumpReportBot->recordBaseCurrencies();

    }
}