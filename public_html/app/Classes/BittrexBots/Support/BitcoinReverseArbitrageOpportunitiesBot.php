<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\BotRunningStatusModel;
use App\Classes\EmailRecipientsUtilities;
use Log;
use Mail;
use Carbon\Carbon;
use App\Models\BitcoinReverseArbitrageOpportunitiesModel;
use App\Classes\CurrenciesUtilities;

class BitcoinReverseArbitrageOpportunitiesBot
{

    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public
    function bitcoinReverseArbitrageOpportunitiesBot()
    {
        $startTime = Carbon::now();
        try {

            $currenciesUtilities = new CurrenciesUtilities();
            $data = $currenciesUtilities->getReverseArbitrageOpportunity();

            if(count($data)>0){
                $percentageDifferenceBlockchainZebpay = $data['percentageDifferenceBlockchainZebpay'];
                $percentageDifferenceLocalBitcoinsZebpay = $data['percentageDifferenceLocalBitcoinsZebpay'];
                if ($percentageDifferenceBlockchainZebpay >= 5 ) {
                    $this->record($data);
                    $this->report($data);
                }
            }

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'Every three hours between 7am to 10pm';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }


    public function record($data)
    {
        try {
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            BitcoinReverseArbitrageOpportunitiesModel::insert($data);
            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->dbAffected = 'BitcoinReverseArbitrageOpportunitiesModel';
            $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

    public function report($data)
    {
        try {
            $mailBody['bitcoinArbitrageOpportunitiesData'] = $data;

            $emailRecipientsUtilities = new EmailRecipientsUtilities();
            $emailRecipients = $emailRecipientsUtilities->getRecipientAddresses();

            $mailData = array(
                'email' => $emailRecipients,
                'from_name' => 'Bitcoin Reverse arbitrage opportunities',
                'from' => 'info@cryptobots.in',
                'subject' => 'Reverse Arbitrage opportunities',
                'mailBody' => $mailBody
            );

            Mail::send('bittrex.emails.BitcoinReverseArbitrageOpportunitiesBot.mail_body', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
            });

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

}