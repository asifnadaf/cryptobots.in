<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordBittrexBTCIndexBot;
use Illuminate\Console\Command;

class Command_RecordBittrexBTCIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordBittrexBTCIndex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records bittrex btc index balance every 24 hours';

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
        $recordBittrexBTCIndexBot = new RecordBittrexBTCIndexBot();
        $recordBittrexBTCIndexBot->setBittrexBTCIndex();

    }
}