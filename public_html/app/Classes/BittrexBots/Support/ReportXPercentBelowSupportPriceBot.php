<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\MarketOddsModel;
use App\Models\BotRunningStatusModel;
use App\Models\BotSettingsModel;
use App\Classes\EmailRecipientsUtilities;

use Log;
use Mail;
use Carbon\Carbon;
use Mockery\Exception;

class ReportXPercentBelowSupportPriceBot
{

    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function XPercentBelowSupportPrice()
    {
        $startTime = Carbon::now();
        try {

            $botSettingRow = BotSettingsModel::first();
            $minimumVolumeOfBaseCurrencyBTC = $botSettingRow->minimumVolumeOfBaseCurrencyBTC;
            $supportAndEqualOddsRatio = $botSettingRow->supportAndEqualOddsRatio;

            $data = MarketOddsModel::whereRaw('Bid <=0.8*supportPrice')->where('supportAndEqualOddsRatio', '>=', $supportAndEqualOddsRatio)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('supportNLastPercentageDifference', 'asc')->get();

            if (count($data) > 0) {
                $notEmailedPriceList = $this->getNotEmailedPriceList($data);
                if (count($notEmailedPriceList) > 0) {
                    $this->reportAltcoinBelowSupportPriceBy20Percent($notEmailedPriceList);
                }
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


    public function getNotEmailedPriceList($data)
    {
        $notEmailedPriceList = [];

        try {

            foreach ($data as $row) {
                $marketRow = MarketOddsModel::where('MarketName', '=', $row->MarketName)->first();
                $isBelowSupportPrice = $marketRow->isBelowSupportPrice;
                if (strcasecmp($isBelowSupportPrice, 'No') == 0) {
                    $notEmailedPriceList[] = $row;
                }
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        return $notEmailedPriceList;

    }


    public function reportAltcoinBelowSupportPriceBy20Percent($notEmailedPriceList)
    {
        try {

            if (count($notEmailedPriceList) > 0) {
                $mailBody['notEmailedPriceList'] = $notEmailedPriceList;
                $emailRecipientsUtilities = new EmailRecipientsUtilities();
                $emailRecipients = $emailRecipientsUtilities->getRecipientsAddresses();

                $mailData = array(
                    'email' => $emailRecipients,
                    'from_name' => 'CryptoBots',
                    'from' => 'info@cryptobots.in',
                    'subject' => 'Altcoins below support price by 20%',
                    'mailBody' => $mailBody
                );

                Mail::send('bittrex.emails.ReportXPercentBelowSupportPriceBot.mail_body', $mailData, function ($message) use ($mailData) {
                    $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
                });

                foreach ($notEmailedPriceList as $row) {
                    $marketRow = MarketOddsModel::where('MarketName', '=', $row->MarketName)->first();
                    $marketRow->isBelowSupportPrice = 'Yes';
                    $marketRow->save();
                }

                $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
                $botRunningStatusData->dbAffected = 'MarketOddsModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();

            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }


}