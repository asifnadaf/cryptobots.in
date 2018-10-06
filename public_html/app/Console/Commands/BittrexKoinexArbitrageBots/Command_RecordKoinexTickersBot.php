<?php

namespace App\Console\Commands\BittrexKoinexArbitrageBots;

use App\Classes\BittrexKoinexArbitrageBots\RecordKoinexTickersBot;
use Illuminate\Console\Command;
use Log;

class Command_RecordKoinexTickersBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordKoinexTickersBot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command records ticker data of Koinex exchange';

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
        $recordKoinexTickersBot = new RecordKoinexTickersBot();
        $recordKoinexTickersBot->runBot();
        sleep(19);
        $recordKoinexTickersBot->runBot();
        sleep(19);
        $recordKoinexTickersBot->runBot();
    }
}