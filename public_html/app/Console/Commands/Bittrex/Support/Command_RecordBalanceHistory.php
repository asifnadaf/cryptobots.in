<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordBalanceHistoryBot;
use Illuminate\Console\Command;

class Command_RecordBalanceHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordBalanceHistory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command saves BTC balance every 24 hours';

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
        $balanceHistoryBot = new RecordBalanceHistoryBot();
        $balanceHistoryBot->setBalanceHistory();

    }
}