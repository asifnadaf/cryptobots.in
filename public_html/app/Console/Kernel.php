<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\BittrexKoinexArbitrageBots\Command_RecordKoinexTickersBot::class,
        Commands\BittrexKoinexArbitrageBots\Command_IndianExchangesArbitrageOpportunitiesTrackerBot::class,
        Commands\BittrexKoinexArbitrageBots\Command_KoinexBittrexArbitrageOpportunitiesTrackerBot::class,
        Commands\Bittrex\Support\Command_BitcoinArbitrageOpportunitiesBot::class,
        Commands\Bittrex\Support\Command_BitcoinReverseArbitrageOpportunitiesBot::class,
        Commands\Bittrex\Support\Command_BuyAtSupport::class,
        Commands\Bittrex\Support\Command_RecordBalanceHistory::class,
        Commands\Bittrex\Support\Command_RecordBaseCurrenciesRate::class,
        Commands\Bittrex\Support\Command_RecordBittrexBTCIndex::class,
        Commands\Bittrex\Support\Command_RecordDailyMarketSummary::class,
        Commands\Bittrex\Support\Command_RecordEveryMinuteMarketOdds::class,
        Commands\Bittrex\Support\Command_RecordMarketDelisting::class,
        Commands\Bittrex\Support\Command_RecordMarketListing::class,
        Commands\Bittrex\Support\Command_RecordPastInvestmentsOpportunities::class,
        Commands\Bittrex\Support\Command_ReportAboveResistancePrice::class,
        Commands\Bittrex\Support\Command_ReportPumpAndDump::class,
        Commands\Bittrex\Support\Command_ReportXPercentBelowSupportPrice::class,
        Commands\Bittrex\Support\Command_UpdateSellLimitOrderBookToXTimes::class,
        Commands\Bittrex\Support\Command_SellOnResistancePrice::class,
        Commands\Bittrex\Support\Command_SendEmailWhenBotsNotWorkingBot::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('CommandBSRBot:RecordKoinexTickersBot')
            ->everyMinute()->withoutOverlapping();

        $schedule->command('CommandBSRBot:Command_IndianExchangesArbitrageOpportunitiesTrackerBot')
            ->everyMinute()->withoutOverlapping();


        $schedule->command('CommandBSRBot:KoinexBittrexArbitrageOpportunitiesTrackerBot')
            ->everyMinute()->withoutOverlapping();


        $schedule->command('CommandBSRBot:IndianExchangesArbitrageBot')
            ->dailyAt('7.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:IndianExchangesArbitrageBot')
            ->dailyAt('10.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:IndianExchangesArbitrageBot')
            ->dailyAt('13.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:IndianExchangesArbitrageBot')
            ->dailyAt('16.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:IndianExchangesArbitrageBot')
            ->dailyAt('19.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:IndianExchangesArbitrageBot')
            ->dailyAt('22.00')->withoutOverlapping();


        $schedule->command('CommandBSRBot:BitcoinReverseArbitrageOpportunitiesBot')
            ->dailyAt('7.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:BitcoinReverseArbitrageOpportunitiesBot')
            ->dailyAt('10.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:BitcoinReverseArbitrageOpportunitiesBot')
            ->dailyAt('13.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:BitcoinReverseArbitrageOpportunitiesBot')
            ->dailyAt('16.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:BitcoinReverseArbitrageOpportunitiesBot')
            ->dailyAt('19.00')->withoutOverlapping();
        $schedule->command('CommandBSRBot:BitcoinReverseArbitrageOpportunitiesBot')
            ->dailyAt('22.00')->withoutOverlapping();


        $schedule->command('CommandBSRBot:BuyAtSupport')
            ->everyMinute()->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordBalanceHistory')
            ->dailyAt('00:01')->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordBaseCurrenciesRate')
            ->everyMinute()->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordBittrexBTCIndex')
            ->dailyAt('00:01')->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordDailyMarketSummary')
            ->dailyAt('00:01')->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordEveryMinuteMarketOdds')
            ->everyMinute()->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordMarketDelisting')
            ->dailyAt('11:00')->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordMarketListing')
            ->dailyAt('10:58')->withoutOverlapping();

        $schedule->command('CommandBSRBot:RecordPastInvestmentsOpportunities')
            ->everyMinute()->withoutOverlapping();

        $schedule->command('CommandBSRBot:ReportAboveResistancePrice')
            ->everyFiveMinutes()->withoutOverlapping();

        $schedule->command('CommandBSRBot:ReportPumpAndDump')
            ->everyFiveMinutes()->withoutOverlapping();

        $schedule->command('CommandBSRBot:ReportXPercentBelowSupportPrice')
            ->everyFiveMinutes()->withoutOverlapping();

        $schedule->command('CommandBSRBot:UpdateSellLimitOrderBookToXTimes')
            ->everyThirtyMinutes()->withoutOverlapping();

        $schedule->command('CommandBSRBot:SellOnResistancePrice')
            ->everyTenMinutes()->withoutOverlapping();

        $schedule->command('CommandBSRBot:SendEmailWhenBotsNotWorkingBot')
            ->everyTenMinutes()->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
