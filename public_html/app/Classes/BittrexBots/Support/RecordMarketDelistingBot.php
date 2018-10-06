<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\MarketOddsModel;
use Log;
use Mail;
use Carbon\Carbon;
use App\Classes\BittrexMarketUtilities;
use App\Classes\EmailRecipientsUtilities;
use App\Models\BotRunningStatusModel;
use App\Models\MarketDelistingModel;
use App\Models\ClientsListModel;
use App\Classes\BittrexAPIs;


class RecordMarketDelistingBot
{
    var $className = null;
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public
    function recordMarketDeListing()
    {
        $startTime = Carbon::now();
        try {
            $bittrexUtilities = new BittrexMarketUtilities(null, null);
            $marketsDelistedData = $bittrexUtilities->delistingMarkets();
            if (count($marketsDelistedData) > 0) {
                $this->record($marketsDelistedData);
                $this->remove($marketsDelistedData);
                $this->sellAltcoin($marketsDelistedData);
                $this->report($marketsDelistedData);
            }

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'dailyAt(\'11:00\')';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }

    public function record($marketsDelistedData)
    {
        try {
            $marketDelistingModelData = MarketDelistingModel::orderBy('created_at', 'asc')->get();
            $flag = false;
            foreach ($marketDelistingModelData as $existingTableRow) {
                foreach ($marketsDelistedData as $toBeInsertedRow) {
                    if (strcasecmp($existingTableRow->MarketName, $toBeInsertedRow['MarketName']) == 0) {
                        if (strcasecmp($existingTableRow->Notice, $toBeInsertedRow['Notice']) != 0) {
                            MarketDelistingModel::insert($toBeInsertedRow);
                            $flag = true;
                        }
                    }
                }
            }

            if ($flag) {
                $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
                $botRunningStatusData->dbAffected = 'MarketDelistingModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();

            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

    public function remove($marketsDelistedData)
    {
        try {
            foreach ($marketsDelistedData as $toBeDeletedRow) {
                $toBeRemovedDBData = MarketOddsModel::where('MarketName', '=', $toBeDeletedRow['MarketName'])->first();
                if ($toBeRemovedDBData !== null) {
                    MarketOddsModel::destroy($toBeRemovedDBData->id);
                }
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

    public function sellAltcoin($marketsDelistedData)
    {

        foreach ($marketsDelistedData as $toBeSoldAltcoin) {

            $marketName = $toBeSoldAltcoin['MarketName'];

            $bittrexMarketUtilities = new BittrexMarketUtilities(null, null);
            $ticker = $bittrexMarketUtilities->getTickerDataFromDB($marketName);
            $rate = $ticker->Bid;

            $clientsList = ClientsListModel::all();
            foreach ($clientsList as $client) {

                $bittrexAPIs = new BittrexAPIs($client->apiKey, $client->secretKey);

                $altcoinsName = str_replace("BTC-", "", $marketName);
                $alcoinBalance = $bittrexAPIs->getBalance($altcoinsName);

                $quantity = $alcoinBalance->Balance;

                if ($quantity * $rate >= $this->bittrexMinimumInvestmentLimits) {
                    $openOrders = $bittrexAPIs->getOpenOrders($marketName);
                    if (count($openOrders)) {
                        foreach ($openOrders as $openOrder)
                            $orderUuid = $openOrder->OrderUuid;
                        if ($orderUuid != null) {
                            $bittrexAPIs->cancel($orderUuid);
                            sleep(1);
                        }
                    }
                    $bittrexAPIs->sellLimit($marketName, $quantity, $rate);
                } else {
                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
                }
            }
        }
    }

    public function report($marketsDelistedData)
    {
        try {
            $mailBody['marketsDelistedData'] = $marketsDelistedData;

            $emailRecipientsUtilities = new EmailRecipientsUtilities();
            $emailRecipients = $emailRecipientsUtilities->getRecipientsAddresses();

            $mailData = array(
                'email' => $emailRecipients,
                'from_name' => 'CryptoBots',
                'from' => 'info@cryptobots.in',
                'subject' => 'Markets Delisting',
                'mailBody' => $mailBody
            );

            Mail::send('bittrex.emails.RecordMarketDelistingBot.mail_body', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
            });

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

}