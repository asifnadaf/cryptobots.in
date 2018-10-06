<?php

namespace App\Classes\BittrexBots\Support;

use App\Classes\BittrexMarketUtilities;
use App\Models\MarketPumpsModel;
use App\Models\MarketDumpsModel;
use App\Models\BotRunningStatusModel;
use App\Classes\EmailRecipientsUtilities;
use Log;
use Mail;
use Carbon\Carbon;

class ReportPumpAndDumpBot
{

    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public
    function pumpAndDumpReport()
    {
        $startTime = Carbon::now();
        try {

            $bittrexUtilities = new BittrexMarketUtilities(null, null);
            $marketSummaries = $bittrexUtilities->getMarketSummariesWithDailyPercentageReturnsAndReturnAsArray();
            $marketSummaries = $this->getBTCMarkets($marketSummaries);

            $marketPumps = $this->getPumps($marketSummaries);
            if ($marketPumps != null) {
                $this->recordPumps($marketPumps);
                $this->reportPumps($marketPumps);
            }

            $marketDumps = $this->getDumps($marketSummaries);
            if ($marketDumps != null) {
                $this->recordDumps($marketDumps);
                $this->reportDumps($marketDumps);
            }

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'Every 5 minutes';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }

    public function getBTCMarkets($marketSummaries)
    {
        $BTCPrefix = 'BTC-';
        $btcMarkets = [];
        foreach ($marketSummaries as $row){
            if (strpos($row['MarketName'], $BTCPrefix) !== false) {
                $btcMarkets[] = $row;
            }
        }
        return $btcMarkets;
    }

    public function getPumps($marketSummaries)
    {
        $marketPumps = null;
        try {
            $oneDaysBack = Carbon::now()->subDays(1);
            foreach ($marketSummaries as $marketSummary) {
                if ($marketSummary['percentChange'] >= 100) {
                    $record = MarketPumpsModel::where('created_at', '>=', $oneDaysBack)->where('marketName', '=', $marketSummary['MarketName'])->first();
                    if (count($record) == 0) {
                        $marketPumps[] = $marketSummary;
                    }
                }
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $marketPumps;
    }

    public function getDumps($marketSummaries)
    {
        $marketDumps = null;
        try {
            $oneDaysBack = Carbon::now()->subDays(1);
            foreach ($marketSummaries as $marketSummary) {
                if ($marketSummary['percentChange'] <= -50) {
                    $record = MarketDumpsModel::where('created_at', '>=', $oneDaysBack)->where('marketName', '=', $marketSummary['MarketName'])->first();
                    if (count($record) == 0) {
                        $marketDumps[] = $marketSummary;
                    }
                }
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $marketDumps;
    }

    public function recordPumps($marketPumps)
    {
        try {
            MarketPumpsModel::insert($marketPumps);
            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->dbAffected = 'MarketPumpsModel & MarketDumpsModel';
            $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
            $botRunningStatusData->save();
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

    public function recordDumps($marketDumps)
    {
        try {
            MarketDumpsModel::insert($marketDumps);
            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->dbAffected = 'MarketPumpsModel & MarketDumpsModel';
            $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
            $botRunningStatusData->save();
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }


    public function reportPumps($marketPumps)
    {
        try {
            $mailBody['marketPumps'] = $marketPumps;
            $emailRecipientsUtilities = new EmailRecipientsUtilities();
            $emailRecipients = $emailRecipientsUtilities->getRecipientsAddresses();

            $mailData = array(
                'email' => $emailRecipients,
                'from_name' => 'CryptoBots',
                'from' => 'info@cryptobots.in',
                'subject' => 'Market Pumps',
                'mailBody' => $mailBody
            );

            Mail::send('bittrex.emails.ReportPumpAndDumpBot.pump_mail_body', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
            });

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

    public function reportDumps($marketDumps)
    {
        try {
            $mailBody['marketDumps'] = $marketDumps;
            $emailRecipientsUtilities = new EmailRecipientsUtilities();
            $emailRecipients = $emailRecipientsUtilities->getRecipientsAddresses();

            $mailData = array(
                'email' => $emailRecipients,
                'from_name' => 'CryptoBots',
                'from' => 'info@cryptobots.in',
                'subject' => 'Market Dumps',
                'mailBody' => $mailBody
            );

            Mail::send('bittrex.emails.ReportPumpAndDumpBot.dump_mail_body', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
            });

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

}