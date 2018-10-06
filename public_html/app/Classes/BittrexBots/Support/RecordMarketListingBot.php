<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\MarketOddsModel;
use Log;
use Mail;
use Carbon\Carbon;
use App\Classes\BittrexMarketUtilities;
use App\Models\BotRunningStatusModel;
use App\Models\MarketListingModel;
use App\Classes\EmailRecipientsUtilities;

class RecordMarketListingBot
{
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public
    function recordMarketListing()
    {
        $startTime = Carbon::now();
        try {
            $bittrexUtilities = new BittrexMarketUtilities(null, null);
            $newMarketsListedData = $bittrexUtilities->newlyListedMarkets();
            if (count($newMarketsListedData) > 0) {
                $this->record($newMarketsListedData);
                $this->report($newMarketsListedData);
            }

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'dailyAt(\'10:58\')';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }

    public function record($newMarketsListedData)
    {

        $flag = false;
        try {
            foreach ($newMarketsListedData as $toBeAddedMarket) {
                $isDBRecordExist = MarketListingModel::where('MarketName', '=', $toBeAddedMarket['MarketName'])->first();
                if ($isDBRecordExist === null) {
                    MarketListingModel::insert($toBeAddedMarket);
                    $toBeAddedMarket['exchangeName'] = "Bittrex";
                    MarketOddsModel::insert($toBeAddedMarket);
                    $flag = true;
                }
            }

            if ($flag) {
                $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
                $botRunningStatusData->dbAffected = 'MarketListingModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }

    public function report($newMarketsListedData)
    {

        try {
            $mailBody['newMarketsListedData'] = $newMarketsListedData;

            $emailRecipientsUtilities = new EmailRecipientsUtilities();
            $emailRecipients = $emailRecipientsUtilities->getRecipientsAddresses();

            $mailData = array(
                'email' => $emailRecipients,
                'from_name' => 'CryptoBots',
                'from' => 'info@cryptobots.in',
                'subject' => 'New Markets Listing',
                'mailBody' => $mailBody
            );

            Mail::send('bittrex.emails.RecordMarketListingBot.mail_body', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
            });

            foreach ($newMarketsListedData as $row) {
                $marketName = $row['MarketName'];
                $marketListingDataRow = MarketListingModel::where('MarketName', '=', $marketName)->first();
                $marketListingDataRow->isEmailed = true;
                $marketListingDataRow->save();
            }


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }
}