<?php

namespace App\Classes\IndianExchangesArbitrageBot;

use App\Models\BotRunningStatusModel;
use Log;
use Mail;
use Carbon\Carbon;
use App\Models\IndianExchangesArbitrageOpportunitiesTrackerModel;

class IndianExchangesArbitrageBot
{

    var $className = null;
    var $pocketBitsExchangeTickerData = null;
    var $koinexExchangeTickerData = null;
    var $coinomeExchangeTickerData = null;
    var $grossPercentGain = 1.35;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
        $this->pocketBitsExchangeTickerData = $this->pocketBitsExchangeTicker();
        $this->koinexExchangeTickerData = $this->koinexExchangeTicker();
        $this->coinomeExchangeTickerData = $this->coinomeExchangeTicker();


    }

    public
    function runBot()
    {


        $startTime = Carbon::now();
        try {

            $data = $this->findCoinomeKoinexArbitrageOpportunites();
            $this->record($data);
            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->runsEvery = 'Every minute';
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $endTime = Carbon::now();
        $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);


    }


    public
    function pocketBitsExchangeTicker()
    {
        try {

            $url = "https://pocketbits.in/api/ticker";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $html = curl_exec($ch);
            curl_close($ch);

            $tickers = json_decode($html, true);
            return $tickers;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            $tickers = '';
        }
        return $tickers;
    }

    public
    function koinexExchangeTicker()
    {
        try {

            $url = "https://koinex.in/api/ticker";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $html = curl_exec($ch);
            curl_close($ch);

            $tickers = json_decode($html, true);
            return $tickers;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            $tickers = '';
        }
        return $tickers;
    }

    public
    function coinomeExchangeTicker()
    {
        try {

            $url = "https://www.coinome.com/api/v1/ticker.json";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $html = curl_exec($ch);
            curl_close($ch);

            $tickers = json_decode($html, true);
            return $tickers;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            $tickers = '';
        }
        return $tickers;
    }

    public
    function findCoinomeKoinexArbitrageOpportunites()
    {


        $result = [];
        $result['coinomeHighKoinexLow']['isArbitrageOpportunity'] = false;
        $result['koinexHighCoinomeLow']['isArbitrageOpportunity'] = false;

        $result['coinomeHighPocketbitsLow']['isArbitrageOpportunity'] = false;
        $result['pocketbitsHighCoinomeLow']['isArbitrageOpportunity'] = false;

        $result['koinexHighPocketbitsLow']['isArbitrageOpportunity'] = false;
        $result['pocketbitsHighKoinexLow']['isArbitrageOpportunity'] = false;

        $koinexExchangeTickerData = $this->koinexExchangeTickerData;
        $coinomeExchangeTickerData = $this->coinomeExchangeTickerData;
        $pocketBitsExchangeTickerData = $this->pocketBitsExchangeTickerData;


        if (count($koinexExchangeTickerData) > 0 && count($coinomeExchangeTickerData) > 0 && count($pocketBitsExchangeTickerData) > 0) {

            $coinomeAsk = $coinomeExchangeTickerData['BTC-INR']['lowest_ask'];
            $coinomeBid = $coinomeExchangeTickerData['BTC-INR']['highest_bid'];

            $koinexBid = $koinexExchangeTickerData['stats']['BTC']['highest_bid'];
            $koinexAsk = $koinexExchangeTickerData['stats']['BTC']['lowest_ask'];

            $pocketbitsBid = $pocketBitsExchangeTickerData['sell'];
            $pocketbitsBid= bcdiv($pocketbitsBid, 1, 2);

            $pocketbitsAsk = $pocketBitsExchangeTickerData['buy'];
            $pocketbitsAsk= bcdiv($pocketbitsAsk, 1, 2);

            $coinomeHighKoinexLowPercentReturns = ($coinomeBid - $koinexAsk) / $koinexAsk * 100;
            $coinomeHighKoinexLowPercentReturns= bcdiv($coinomeHighKoinexLowPercentReturns, 1, 2);

            $koinexHighCoinomeLowPercentReturns = ($koinexBid - $coinomeAsk) / $coinomeAsk * 100;
            $koinexHighCoinomeLowPercentReturns= bcdiv($koinexHighCoinomeLowPercentReturns, 1, 2);

            $result['coinomeHighKoinexLow']['grossPercentGain'] = $coinomeHighKoinexLowPercentReturns;
            $result['coinomeHighKoinexLow']['sellTransactionValue'] = $coinomeBid;
            $result['coinomeHighKoinexLow']['buyTransactionValue'] = $koinexAsk;
            if ($coinomeHighKoinexLowPercentReturns >= $this->grossPercentGain) {
                $result['coinomeHighKoinexLow']['isArbitrageOpportunity'] = true;
            }

            $result['koinexHighCoinomeLow']['grossPercentGain'] = $koinexHighCoinomeLowPercentReturns;
            $result['koinexHighCoinomeLow']['sellTransactionValue'] = $koinexBid;
            $result['koinexHighCoinomeLow']['buyTransactionValue'] = $coinomeAsk;
            if ($koinexHighCoinomeLowPercentReturns >= $this->grossPercentGain) {
                $result['koinexHighCoinomeLow']['isArbitrageOpportunity'] = true;
            }


            $coinomeHighPocketbitsLowPercentReturns = ($coinomeBid - $pocketbitsAsk) / $pocketbitsAsk * 100;
            $coinomeHighPocketbitsLowPercentReturns= bcdiv($coinomeHighPocketbitsLowPercentReturns, 1, 2);

            $pocketbitsHighCoinomeLowPercentReturns = ($pocketbitsBid - $coinomeAsk) / $coinomeAsk * 100;
            $pocketbitsHighCoinomeLowPercentReturns= bcdiv($pocketbitsHighCoinomeLowPercentReturns, 1, 2);

            $result['coinomeHighPocketbitsLow']['grossPercentGain'] = $coinomeHighPocketbitsLowPercentReturns;
            $result['coinomeHighPocketbitsLow']['sellTransactionValue'] = $coinomeBid;
            $result['coinomeHighPocketbitsLow']['buyTransactionValue'] = $pocketbitsAsk;
            if ($coinomeHighPocketbitsLowPercentReturns >= $this->grossPercentGain) {
                $result['coinomeHighPocketbitsLow']['isArbitrageOpportunity'] = true;
            }

            $result['pocketbitsHighCoinomeLow']['grossPercentGain'] = $pocketbitsHighCoinomeLowPercentReturns;
            $result['pocketbitsHighCoinomeLow']['sellTransactionValue'] = $pocketbitsBid;
            $result['pocketbitsHighCoinomeLow']['buyTransactionValue'] = $coinomeAsk;;
            if ($pocketbitsHighCoinomeLowPercentReturns >= $this->grossPercentGain) {
                $result['pocketbitsHighCoinomeLow']['isArbitrageOpportunity'] = true;
            }


            $koinexHighPocketbitsLowPercentReturns = ($koinexBid - $pocketbitsAsk) / $pocketbitsAsk * 100;
            $koinexHighPocketbitsLowPercentReturns= bcdiv($koinexHighPocketbitsLowPercentReturns, 1, 2);

            $pocketbitsHighKoinexLowPercentReturns = ($pocketbitsBid - $koinexAsk) / $koinexAsk * 100;
            $pocketbitsHighKoinexLowPercentReturns= bcdiv($pocketbitsHighKoinexLowPercentReturns, 1, 2);

            $result['koinexHighPocketbitsLow']['grossPercentGain'] = $koinexHighPocketbitsLowPercentReturns;
            $result['koinexHighPocketbitsLow']['sellTransactionValue'] = $koinexBid;
            $result['koinexHighPocketbitsLow']['buyTransactionValue'] = $pocketbitsAsk;
            if ($koinexHighPocketbitsLowPercentReturns >= $this->grossPercentGain) {
                $result['koinexHighPocketbitsLow']['isArbitrageOpportunity'] = true;
            }

            $result['pocketbitsHighKoinexLow']['grossPercentGain'] = $pocketbitsHighKoinexLowPercentReturns;
            $result['pocketbitsHighKoinexLow']['sellTransactionValue'] = $pocketbitsBid;
            $result['pocketbitsHighKoinexLow']['buyTransactionValue'] = $koinexAsk;
            if ($pocketbitsHighKoinexLowPercentReturns >= $this->grossPercentGain) {
                $result['pocketbitsHighKoinexLow']['isArbitrageOpportunity'] = true;
            }


        } else {
            if (count($koinexExchangeTickerData) > 0) {
                $result['message'] = 'Koinex data is not available';
            } elseif (count($coinomeExchangeTickerData) > 0) {
                $result['message'] = 'Coinome data is not available';
            } elseif (count($pocketBitsExchangeTickerData) > 0) {
                $result['message'] = 'Pocketbits data is not available';
            }
        }


        return $result;

    }


    public function record($data)
    {
        try {

            $isDBTableAffected = false;

            if ($data['coinomeHighKoinexLow']['isArbitrageOpportunity'] == true) {
                $indianExchangesArbitrageOpportunitiesTrackerModel = new IndianExchangesArbitrageOpportunitiesTrackerModel();

                $indianExchangesArbitrageOpportunitiesTrackerModel->opportunityType = 'coinomeHighKoinexLow';
                $indianExchangesArbitrageOpportunitiesTrackerModel->buyTransactionValue = $data['coinomeHighKoinexLow']['buyTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->sellTransactionValue = $data['coinomeHighKoinexLow']['sellTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->grossPercentGain = $data['coinomeHighKoinexLow']['grossPercentGain'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->created_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->updated_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->save();
                $isDBTableAffected = true;

            }

            if ($data['koinexHighCoinomeLow']['isArbitrageOpportunity'] == true) {
                $indianExchangesArbitrageOpportunitiesTrackerModel = new IndianExchangesArbitrageOpportunitiesTrackerModel();

                $indianExchangesArbitrageOpportunitiesTrackerModel->opportunityType = 'koinexHighCoinomeLow';
                $indianExchangesArbitrageOpportunitiesTrackerModel->buyTransactionValue = $data['koinexHighCoinomeLow']['buyTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->sellTransactionValue = $data['koinexHighCoinomeLow']['sellTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->grossPercentGain = $data['koinexHighCoinomeLow']['grossPercentGain'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->created_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->updated_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->save();
                $isDBTableAffected = true;

            }


            if ($data['coinomeHighPocketbitsLow']['isArbitrageOpportunity'] == true) {
                $indianExchangesArbitrageOpportunitiesTrackerModel = new IndianExchangesArbitrageOpportunitiesTrackerModel();

                $indianExchangesArbitrageOpportunitiesTrackerModel->opportunityType = 'coinomeHighPocketbitsLow';
                $indianExchangesArbitrageOpportunitiesTrackerModel->buyTransactionValue = $data['coinomeHighPocketbitsLow']['buyTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->sellTransactionValue = $data['coinomeHighPocketbitsLow']['sellTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->grossPercentGain = $data['coinomeHighPocketbitsLow']['grossPercentGain'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->created_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->updated_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->save();
                $isDBTableAffected = true;

            }


            if ($data['pocketbitsHighCoinomeLow']['isArbitrageOpportunity'] == true) {
                $indianExchangesArbitrageOpportunitiesTrackerModel = new IndianExchangesArbitrageOpportunitiesTrackerModel();

                $indianExchangesArbitrageOpportunitiesTrackerModel->opportunityType = 'pocketbitsHighCoinomeLow';
                $indianExchangesArbitrageOpportunitiesTrackerModel->buyTransactionValue = $data['pocketbitsHighCoinomeLow']['buyTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->sellTransactionValue = $data['pocketbitsHighCoinomeLow']['sellTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->grossPercentGain = $data['pocketbitsHighCoinomeLow']['grossPercentGain'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->created_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->updated_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->save();
                $isDBTableAffected = true;

            }


            if ($data['koinexHighPocketbitsLow']['isArbitrageOpportunity'] == true) {
                $indianExchangesArbitrageOpportunitiesTrackerModel = new IndianExchangesArbitrageOpportunitiesTrackerModel();

                $indianExchangesArbitrageOpportunitiesTrackerModel->opportunityType = 'koinexHighPocketbitsLow';
                $indianExchangesArbitrageOpportunitiesTrackerModel->buyTransactionValue = $data['koinexHighPocketbitsLow']['buyTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->sellTransactionValue = $data['koinexHighPocketbitsLow']['sellTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->grossPercentGain = $data['koinexHighPocketbitsLow']['grossPercentGain'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->created_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->updated_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->save();
                $isDBTableAffected = true;

            }

            if ($data['pocketbitsHighKoinexLow']['isArbitrageOpportunity'] == true) {
                $indianExchangesArbitrageOpportunitiesTrackerModel = new IndianExchangesArbitrageOpportunitiesTrackerModel();

                $indianExchangesArbitrageOpportunitiesTrackerModel->opportunityType = 'pocketbitsHighKoinexLow';
                $indianExchangesArbitrageOpportunitiesTrackerModel->buyTransactionValue = $data['pocketbitsHighKoinexLow']['buyTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->sellTransactionValue = $data['pocketbitsHighKoinexLow']['sellTransactionValue'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->grossPercentGain = $data['pocketbitsHighKoinexLow']['grossPercentGain'];
                $indianExchangesArbitrageOpportunitiesTrackerModel->created_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->updated_at = Carbon::now();
                $indianExchangesArbitrageOpportunitiesTrackerModel->save();
                $isDBTableAffected = true;

            }

            if ($isDBTableAffected) {
                $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
                $botRunningStatusData->dbAffected = 'IndianExchangesArbitrageOpportunitiesModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

}