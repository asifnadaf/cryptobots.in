<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\BotRunningStatusModel;
use App\Models\ClientsListModel;
use App\Classes\BittrexGeneralUtilities;

use Log;
use Mail;
use Carbon\Carbon;

class RecordBalanceHistoryBot
{

    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function setBalanceHistory()
    {
        $startTime = Carbon::now();
        try {

            $clientsList = ClientsListModel::all();
            foreach ($clientsList as $row) {
                $apiKey = $row->apiKey;
                $secretKey = $row->secretKey;

                $bittrexGeneralUtilities = new BittrexGeneralUtilities($apiKey,$secretKey);
                $bittrexGeneralUtilities->setBalanceHistory($row->id);
            }

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'dailyAt(\'00:01\')';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);

    }

}