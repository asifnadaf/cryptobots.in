<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\BotRunningStatusModel;
use App\Classes\EmailRecipientsUtilities;
use Log;
use Mail;
use Carbon\Carbon;
use App\Models\BitcoinArbitrageOpportunitiesModel;
use App\Classes\CurrenciesUtilities;

class BitcoinArbitrageOpportunitiesBot
{

    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public
    function bitcoinArbitrageOpportunitiesBot()
    {
        $startTime = Carbon::now();
        $data = [];
        try {

            $currenciesUtilities = new CurrenciesUtilities();
            $data = $currenciesUtilities->getArbitrageOpportunity();

            if(count($data)>0){
                $percentageDifferenceBlockchainZebpay = $data['percentageDifferenceBlockchainZebpay'];
                $percentageDifferenceLocalBitcoinsZebpay = $data['percentageDifferenceLocalBitcoinsZebpay'];
                if ($percentageDifferenceBlockchainZebpay >= 10 || $percentageDifferenceLocalBitcoinsZebpay >= 15.5 ) {
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

        return $data;
    }


    public function record($data)
    {
        try {
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();

            BitcoinArbitrageOpportunitiesModel::insert($data);
            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->dbAffected = 'BitcoinArbitrageOpportunitiesModel';
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
                'from_name' => 'Bitcoin arbitrage opportunities',
                'from' => 'info@cryptobots.in',
                'subject' => 'Arbitrage opportunities',
                'mailBody' => $mailBody
            );

            Mail::send('bittrex.emails.IndianExchangesArbitrageBot.mail_body', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
            });

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

}