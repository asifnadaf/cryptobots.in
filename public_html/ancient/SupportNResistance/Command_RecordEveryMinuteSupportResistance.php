<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\RecordEveryMinuteSupportResistanceBot;
use Illuminate\Console\Command;

class Command_RecordEveryMinuteSupportResistance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:RecordEveryMinuteSupportResistance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command saves support resistance information into database every 1 minute';

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
        $recordEveryMinuteSupportResistanceBot = new RecordEveryMinuteSupportResistanceBot();
        $recordEveryMinuteSupportResistanceBot->recordEveryMinuteSupportResistance();

    }
}