<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordPastInvestmentsOpportunitiesBot;
use Illuminate\Console\Command;
use Log;

class Command_RecordPastInvestmentsOpportunities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordPastInvestmentsOpportunities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records keeps track all support and resistance prices opportunities';

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
        $reportMarketDelistingBot = new RecordPastInvestmentsOpportunitiesBot();
        $reportMarketDelistingBot->recordPastInvestmentsOpportunities();

    }
}