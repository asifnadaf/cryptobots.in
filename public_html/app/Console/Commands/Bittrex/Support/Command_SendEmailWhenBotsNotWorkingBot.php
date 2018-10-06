<?php

namespace App\Console\Commands\Bittrex\Support;

use App\Classes\BittrexBots\Support\SendEmailWhenBotsNotWorkingBot;
use Illuminate\Console\Command;
use App\Models\BotSettingsModel;

class Command_SendEmailWhenBotsNotWorkingBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CommandBSRBot:SendEmailWhenBotsNotWorkingBot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command checks if each bot is working and sends an email if one of the bots is not working';

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
        $sendEmailWhenBotsNotWorkingBot = new SendEmailWhenBotsNotWorkingBot();
        $sendEmailWhenBotsNotWorkingBot->runBot();

    }
}