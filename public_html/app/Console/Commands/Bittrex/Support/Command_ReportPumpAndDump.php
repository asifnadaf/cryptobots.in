<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\ReportPumpAndDumpBot;
use Illuminate\Console\Command;
use Log;

class Command_ReportPumpAndDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:ReportPumpAndDump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command reports whenever altcoin is pumped or dumped.';

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
        $pumpAndDumpReportBot = new ReportPumpAndDumpBot();
        $pumpAndDumpReportBot->pumpAndDumpReport();

    }
}