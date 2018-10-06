<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordMarketDelistingBot;
use Illuminate\Console\Command;
use Log;

class Command_RecordMarketDelisting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordMarketDelisting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records whenever any market is delisted or undergoes maintenance.';

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
        $reportMarketDelistingBot = new RecordMarketDelistingBot();
        $reportMarketDelistingBot->recordMarketDeListing();

    }
}