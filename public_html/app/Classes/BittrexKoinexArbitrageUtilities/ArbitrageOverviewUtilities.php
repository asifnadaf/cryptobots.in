<?php

namespace App\Classes\BittrexKoinexArbitrageUtilities;

use Log;
use Carbon\Carbon;
use App\Models\BittrexKoinexArbitrageSettingsModel;
use App\Classes\CurrenciesUtilities;

class ArbitrageOverviewUtilities
{
    var $delayInSecondsPriceUpdates = 25;
    var $delayInSecondsVolumeUpdates = 3;
    var $checkVolumeLastUpdatedTimestamp = false;
    var $BTCToUSDRate = null;

    public function __construct()
    {
        $settings = BittrexKoinexArbitrageSettingsModel::first();

        $value = $settings->lookAtKoinexTickerVolumeUpdateTimestamp;

        if (strcasecmp($value, 'No') == 0) {
            $this->checkVolumeLastUpdatedTimestamp = false;
        } elseif (strcasecmp($value, 'Yes') == 0) {
            $this->checkVolumeLastUpdatedTimestamp = true;
        }

        $currenciesUtilities = new CurrenciesUtilities();
        $this->BTCToUSDRate = $currenciesUtilities->getUSDBTCRateFromDB();

    }


    public function findOpportunities()
    {
        $result = [];

        $result['BCCBittrexUpKoinexDown']['isArbitrageOpportunity'] = false;
        $result['BCCKoinexUpBittrexDown']['isArbitrageOpportunity'] = false;

        $result['ETHBittrexUpKoinexDown']['isArbitrageOpportunity'] = false;
        $result['ETHKoinexUpBittrexDown']['isArbitrageOpportunity'] = false;

        $result['LTCBittrexUpKoinexDown']['isArbitrageOpportunity'] = false;
        $result['LTCKoinexUpBittrexDown']['isArbitrageOpportunity'] = false;

        $result['XRPBittrexUpKoinexDown']['isArbitrageOpportunity'] = false;
        $result['XRPKoinexUpBittrexDown']['isArbitrageOpportunity'] = false;

        
        $settings = BittrexKoinexArbitrageSettingsModel::first();

        $bittrexKoinexArbitrageUtilities = new BittrexKoinexArbitrageUtilities();
        $koinexTickerDataFromDB = $bittrexKoinexArbitrageUtilities->getKoinexTickerFromDB();

        $koinexBTCTickerData = null;
        $koinexBCCTickerData = null;
        $koinexETHTickerData = null;
        $koinexLTCTickerData = null;
        $koinexXRPTickerData = null;
        foreach ($koinexTickerDataFromDB as $row) {
            if (strcasecmp($row->currencyName, 'BTC') == 0) {
                $koinexBTCTickerData = $row;
            }
            if (strcasecmp($row->currencyName, 'BCC') == 0) {
                $koinexBCCTickerData = $row;
            }
            if (strcasecmp($row->currencyName, 'ETH') == 0) {
                $koinexETHTickerData = $row;
            }
            if (strcasecmp($row->currencyName, 'LTC') == 0) {
                $koinexLTCTickerData = $row;
            }
            if (strcasecmp($row->currencyName, 'XRP') == 0) {
                $koinexXRPTickerData = $row;
            }
        }


        $koinexTickerVolumeDataFromDB = $bittrexKoinexArbitrageUtilities->getKoinexTickerVolumeFromDB();

        $koinexBTCTickerVolumeData = null;
        $koinexBCCTickerVolumeData = null;
        $koinexETHTickerVolumeData = null;
        $koinexLTCTickerVolumeData = null;
        $koinexXRPTickerVolumeData = null;
        foreach ($koinexTickerVolumeDataFromDB as $row) {
            if (strcasecmp($row->currencyName, 'BTC') == 0) {
                $koinexBTCTickerVolumeData = $row;
            }
            if (strcasecmp($row->currencyName, 'BCC') == 0) {
                $koinexBCCTickerVolumeData = $row;
            }
            if (strcasecmp($row->currencyName, 'ETH') == 0) {
                $koinexETHTickerVolumeData = $row;
            }
            if (strcasecmp($row->currencyName, 'LTC') == 0) {
                $koinexLTCTickerVolumeData = $row;
            }
            if (strcasecmp($row->currencyName, 'XRP') == 0) {
                $koinexXRPTickerVolumeData = $row;
            }
        }


        $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-BCC');
        if ($bittrexTickerResponse != null) {
            $result['BCCBittrexUpKoinexDown'] = $this->findOpportunityBCCBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexBCCTickerData, $koinexBCCTickerVolumeData, $bittrexTickerResponse);
            $result['BCCKoinexUpBittrexDown'] = $this->findOpportunityBCCKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexBCCTickerData, $koinexBCCTickerVolumeData, $bittrexTickerResponse);
        } else {
            $message = 'Bittrex market BTC-BCC is not available';
            $result['message'] = $message;
        }

        $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-ETH');
        if ($bittrexTickerResponse != null) {
            $result['ETHBittrexUpKoinexDown'] = $this->findOpportunityETHBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexETHTickerData, $koinexETHTickerVolumeData, $bittrexTickerResponse);
            $result['ETHKoinexUpBittrexDown'] = $this->findOpportunityETHKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexETHTickerData, $koinexETHTickerVolumeData, $bittrexTickerResponse);
        } else {
            $message = 'Bittrex market BTC-ETH is not available';
            $result['message'] = $message;
        }

        $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-LTC');
        if ($bittrexTickerResponse != null) {
            $result['LTCBittrexUpKoinexDown'] = $this->findOpportunityLTCBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexLTCTickerData, $koinexLTCTickerVolumeData, $bittrexTickerResponse);
            $result['LTCKoinexUpBittrexDown'] = $this->findOpportunityLTCKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexLTCTickerData, $koinexLTCTickerVolumeData, $bittrexTickerResponse);
        } else {
            $message = 'Bittrex market BTC-LTC is not available';
            $result['message'] = $message;
        }

        $bittrexTickerResponse = $bittrexKoinexArbitrageUtilities->getBittrexOrderBook('BTC-XRP');
        if ($bittrexTickerResponse != null) {
            $result['XRPBittrexUpKoinexDown'] = $this->findOpportunityXRPBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexXRPTickerData, $koinexXRPTickerVolumeData, $bittrexTickerResponse);
            $result['XRPKoinexUpBittrexDown'] = $this->findOpportunityXRPKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexXRPTickerData, $koinexXRPTickerVolumeData, $bittrexTickerResponse);
        } else {
            $message = 'Bittrex market BTC-XRP is not available';
            $result['message'] = $message;
        }

        return $result;

    }


    public function findOpportunityBCCBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexBCCTickerData, $koinexBCCTickerVolumeData, $bittrexTickerResponse)
    {
        $koinexBCCDecimals = 3;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexBCCDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexBCCTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexBCCTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexBCCTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexBCCTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexBCCTickerData) > 0 && count($koinexBTCTickerData) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexBCCTickerData->updated_at);

            $bittrexBCCRate = bcdiv($bittrexTickerResponse->buy[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexBCCQuantity = bcdiv($bittrexTickerResponse->buy[0]->Quantity, 1, $bittrexBCCDecimals);

            $koinexBCCRate = bcdiv($koinexBCCTickerData->ask, 1, $koinexINRDecimals);
            $koinexBCCQuantity = bcdiv($koinexBCCTickerVolumeData->sellVolume, 1, $koinexBCCDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->bid, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->buyVolume, 1, $koinexBTCDecimals);

            $bittrexBCCTransactionValueInINR = ($bittrexBCCRate * $bittrexBCCQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexBCCTransactionValueInINR = $koinexBCCRate * $koinexBCCQuantity;
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $bittrexBCCTransactionValueInINR, $koinexBCCTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $bittrexBCCQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexBCCRate; // (btc value) / btc rate of bcc = bcc quantity
            $koinexBCCQuantity = $maxTranscationValueInINR / $koinexBCCRate;

            $bittrexBCCQuantity = $koinexBCCQuantity = min($bittrexBCCQuantity, $koinexBCCQuantity);

            $koinexBTCQuantity = $bittrexBCCRate * $bittrexBCCQuantity;

            $bittrexBCCQuantity = bcdiv($bittrexBCCQuantity, 1, $koinexBCCDecimals); // $koinexBCCDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexBCCQuantity = bcdiv($koinexBCCQuantity, 1, $koinexBCCDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $BCC_BTC_Bittrex_Rate_In_INR = $bittrexBCCRate * $koinexBTCRate; // The first number on right side is btc value
            $arbitrageReturns = ($BCC_BTC_Bittrex_Rate_In_INR - $koinexBCCRate) / $koinexBCCRate * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);

            $result['BCCPerDollarRate'] = bcdiv($koinexBCCRate/($this->BTCToUSDRate*$bittrexBCCRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['BTC-BCC']['Rate'] = $bittrexBCCRate;
            $result['data']['BTC-BCC']['Quantity'] = $bittrexBCCQuantity;
            $result['data']['BTC-BCC']['Amount'] = bcdiv($bittrexBCCRate * $bittrexBCCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-BCC']['Rate'] = $koinexBCCRate;
            $result['data']['INR-BCC']['Quantity'] = $koinexBCCQuantity;
            $result['data']['INR-BCC']['Amount'] = bcdiv($koinexBCCRate * $koinexBCCQuantity, 1, $koinexINRDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv($koinexBTCRate * $koinexBTCQuantity, 1, $koinexINRDecimals);

            $result['data']['buyTransactionValue'] = bcdiv($koinexBCCRate * $koinexBCCQuantity, 1, $koinexINRDecimals);
            $result['data']['sellTransactionValue'] = bcdiv($bittrexBCCRate * $bittrexBCCQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $bittrexBCCRate = bcdiv($bittrexBCCRate * (1 - $settings->bittrexAskBelowHighestBidByPercent / 100), 1, $bittrexBTCDecimals);
            $koinexBCCRate = bcdiv($koinexBCCRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100), 1, $koinexINRDecimals);
            $koinexBTCRate = bcdiv($koinexBTCRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Buy ' . $koinexBCCQuantity . ' units of BCC @ Rs. ' . $koinexBCCRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexBCCQuantity * $koinexBCCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Sell ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to get a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Sell ' . $bittrexBCCQuantity . ' units of BCC @ BTC ' . $bittrexBCCRate . ' per unit to get a total of BTC ' . bcdiv($bittrexBCCQuantity * $bittrexBCCRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxBCCQuantity * $bittrexBCCRate because the result is after the transaction is not in our control
                $instructions ['BCC_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }


    public function findOpportunityETHBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexETHTickerData, $koinexETHTickerVolumeData, $bittrexTickerResponse)
    {

        $koinexETHDecimals = 3;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexETHDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexETHTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexETHTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexETHTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexETHTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexETHTickerData) > 0 && count($koinexBTCTickerData) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexETHTickerData->updated_at);

            $bittrexETHRate = bcdiv($bittrexTickerResponse->buy[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexETHQuantity = bcdiv($bittrexTickerResponse->buy[0]->Quantity, 1, $bittrexETHDecimals);

            $koinexETHRate = bcdiv($koinexETHTickerData->ask, 1, $koinexINRDecimals);
            $koinexETHQuantity = bcdiv($koinexETHTickerVolumeData->sellVolume, 1, $koinexETHDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->bid, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->buyVolume, 1, $koinexBTCDecimals);

            $bittrexETHTransactionValueInINR = ($bittrexETHRate * $bittrexETHQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexETHTransactionValueInINR = $koinexETHRate * $koinexETHQuantity;
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $bittrexETHTransactionValueInINR, $koinexETHTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $bittrexETHQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexETHRate; // (btc value) / btc rate of ETH = ETH quantity
            $koinexETHQuantity = $maxTranscationValueInINR / $koinexETHRate;

            $bittrexETHQuantity = $koinexETHQuantity = min($bittrexETHQuantity, $koinexETHQuantity);

            $koinexBTCQuantity = $bittrexETHRate * $bittrexETHQuantity;

            $bittrexETHQuantity = bcdiv($bittrexETHQuantity, 1, $koinexETHDecimals); // $koinexETHDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexETHQuantity = bcdiv($koinexETHQuantity, 1, $koinexETHDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $ETH_BTC_Bittrex_Rate_In_INR = $bittrexETHRate * $koinexBTCRate;
            $arbitrageReturns = ($ETH_BTC_Bittrex_Rate_In_INR - $koinexETHRate) / $koinexETHRate * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);


            $result['ETHPerDollarRate'] = bcdiv($koinexETHRate/($this->BTCToUSDRate*$bittrexETHRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['BTC-ETH']['Rate'] = $bittrexETHRate;
            $result['data']['BTC-ETH']['Quantity'] = $bittrexETHQuantity;
            $result['data']['BTC-ETH']['Amount'] = bcdiv($bittrexETHRate * $bittrexETHQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-ETH']['Rate'] = $koinexETHRate;
            $result['data']['INR-ETH']['Quantity'] = $koinexETHQuantity;
            $result['data']['INR-ETH']['Amount'] = bcdiv($koinexETHRate * $koinexETHQuantity, 1, $koinexINRDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv($koinexBTCRate * $koinexBTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['buyTransactionValue'] = bcdiv($koinexETHRate * $koinexETHQuantity, 1, $koinexINRDecimals);
            $result['data']['sellTransactionValue'] = bcdiv($bittrexETHRate * $bittrexETHQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            $bittrexETHRate = bcdiv($bittrexETHRate * (1 - $settings->bittrexAskBelowHighestBidByPercent / 100), 1, $bittrexBTCDecimals);
            $koinexETHRate = bcdiv($koinexETHRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100), 1, $koinexINRDecimals);
            $koinexBTCRate = bcdiv($koinexBTCRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Buy ' . $koinexETHQuantity . ' units of ETH @ Rs. ' . $koinexETHRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexETHQuantity * $koinexETHRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Sell ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to get a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Sell ' . $bittrexETHQuantity . ' units of ETH @ BTC ' . $bittrexETHRate . ' per unit to get a total of BTC ' . bcdiv($bittrexETHQuantity * $bittrexETHRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxETHQuantity * $bittrexETHRate because the result is after the transaction is not in our control
                $instructions ['ETH_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }


    public function findOpportunityLTCBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexLTCTickerData, $koinexLTCTickerVolumeData, $bittrexTickerResponse)
    {

        $koinexLTCDecimals = 3;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexLTCDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexLTCTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexLTCTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexLTCTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexLTCTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexLTCTickerData) > 0 && count($koinexBTCTickerData) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexLTCTickerData->updated_at);

            $bittrexLTCRate = bcdiv($bittrexTickerResponse->buy[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexLTCQuantity = bcdiv($bittrexTickerResponse->buy[0]->Quantity, 1, $bittrexLTCDecimals);

            $koinexLTCRate = bcdiv($koinexLTCTickerData->ask, 1, $koinexINRDecimals);
            $koinexLTCQuantity = bcdiv($koinexLTCTickerVolumeData->sellVolume, 1, $koinexLTCDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->bid, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->buyVolume, 1, $koinexBTCDecimals);

            $bittrexLTCTransactionValueInINR = ($bittrexLTCRate * $bittrexLTCQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexLTCTransactionValueInINR = $koinexLTCRate * $koinexLTCQuantity;
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $bittrexLTCTransactionValueInINR, $koinexLTCTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $bittrexLTCQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexLTCRate; // (btc value) / btc rate of LTC = LTC quantity
            $koinexLTCQuantity = $maxTranscationValueInINR / $koinexLTCRate;

            $bittrexLTCQuantity = $koinexLTCQuantity = min($bittrexLTCQuantity, $koinexLTCQuantity);

            $koinexBTCQuantity = $bittrexLTCRate * $bittrexLTCQuantity;

            $bittrexLTCQuantity = bcdiv($bittrexLTCQuantity, 1, $koinexLTCDecimals); // $koinexLTCDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexLTCQuantity = bcdiv($koinexLTCQuantity, 1, $koinexLTCDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $LTC_BTC_Bittrex_Rate_In_INR = $bittrexLTCRate * $koinexBTCRate;
            $arbitrageReturns = ($LTC_BTC_Bittrex_Rate_In_INR - $koinexLTCRate) / $koinexLTCRate * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);


            $result['LTCPerDollarRate'] = bcdiv($koinexLTCRate/($this->BTCToUSDRate*$bittrexLTCRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['BTC-LTC']['Rate'] = $bittrexLTCRate;
            $result['data']['BTC-LTC']['Quantity'] = $bittrexLTCQuantity;
            $result['data']['BTC-LTC']['Amount'] = bcdiv($bittrexLTCRate * $bittrexLTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-LTC']['Rate'] = $koinexLTCRate;
            $result['data']['INR-LTC']['Quantity'] = $koinexLTCQuantity;
            $result['data']['INR-LTC']['Amount'] = bcdiv($koinexLTCRate * $koinexLTCQuantity, 1, $koinexINRDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv($koinexBTCRate * $koinexBTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['buyTransactionValue'] = bcdiv($koinexLTCRate * $koinexLTCQuantity, 1, $koinexINRDecimals);
            $result['data']['sellTransactionValue'] = bcdiv($bittrexLTCRate * $bittrexLTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            $bittrexLTCRate = bcdiv($bittrexLTCRate * (1 - $settings->bittrexAskBelowHighestBidByPercent / 100), 1, $bittrexBTCDecimals);
            $koinexLTCRate = bcdiv($koinexLTCRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100), 1, $koinexINRDecimals);
            $koinexBTCRate = bcdiv($koinexBTCRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Buy ' . $koinexLTCQuantity . ' units of LTC @ Rs. ' . $koinexLTCRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexLTCQuantity * $koinexLTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Sell ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to get a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Sell ' . $bittrexLTCQuantity . ' units of LTC @ BTC ' . $bittrexLTCRate . ' per unit to get a total of BTC ' . bcdiv($bittrexLTCQuantity * $bittrexLTCRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxLTCQuantity * $bittrexLTCRate because the result is after the transaction is not in our control
                $instructions ['LTC_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }


    public function findOpportunityXRPBittrexUpKoinexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexXRPTickerData, $koinexXRPTickerVolumeData, $bittrexTickerResponse)
    {
        $koinexXRPDecimals = 1;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexXRPDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexXRPTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexXRPTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexXRPTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexXRPTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexXRPTickerData) > 0 && count($koinexBTCTickerData) > 0 && count($bittrexTickerResponse) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexXRPTickerData->updated_at);

            $bittrexXRPRate = bcdiv($bittrexTickerResponse->buy[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexXRPQuantity = bcdiv($bittrexTickerResponse->buy[0]->Quantity, 1, $bittrexXRPDecimals);

            $koinexXRPRate = bcdiv($koinexXRPTickerData->ask, 1, $koinexINRDecimals);
            $koinexXRPQuantity = bcdiv($koinexXRPTickerVolumeData->sellVolume, 1, $koinexXRPDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->bid, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->buyVolume, 1, $koinexBTCDecimals);

            $bittrexXRPTransactionValueInINR = ($bittrexXRPRate * $bittrexXRPQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexXRPTransactionValueInINR = $koinexXRPRate * $koinexXRPQuantity;
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $bittrexXRPTransactionValueInINR, $koinexXRPTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $bittrexXRPQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexXRPRate; // (btc value) / btc rate of XRP = XRP quantity
            $koinexXRPQuantity = $maxTranscationValueInINR / $koinexXRPRate;

            $bittrexXRPQuantity = $koinexXRPQuantity = min($bittrexXRPQuantity, $koinexXRPQuantity);
            $koinexBTCQuantity = $bittrexXRPRate * $bittrexXRPQuantity;

            $bittrexXRPQuantity = bcdiv($bittrexXRPQuantity, 1, $koinexXRPDecimals); // $koinexXRPDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexXRPQuantity = bcdiv($koinexXRPQuantity, 1, $koinexXRPDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $XRP_BTC_Bittrex_Rate_In_INR = $bittrexXRPRate * $koinexBTCRate;
            $arbitrageReturns = ($XRP_BTC_Bittrex_Rate_In_INR - $koinexXRPRate) / $koinexXRPRate * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);


            $result['XRPPerDollarRate'] = bcdiv($koinexXRPRate/($this->BTCToUSDRate*$bittrexXRPRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['BTC-XRP']['Rate'] = $bittrexXRPRate;
            $result['data']['BTC-XRP']['Quantity'] = $bittrexXRPQuantity;
            $result['data']['BTC-XRP']['Amount'] = bcdiv($bittrexXRPRate * $bittrexXRPQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-XRP']['Rate'] = $koinexXRPRate;
            $result['data']['INR-XRP']['Quantity'] = $koinexXRPQuantity;
            $result['data']['INR-XRP']['Amount'] = bcdiv($koinexXRPRate * $koinexXRPQuantity, 1, $koinexINRDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv( $koinexBTCRate * $koinexBTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['buyTransactionValue'] = bcdiv($koinexXRPRate * $koinexXRPQuantity, 1, $koinexINRDecimals);
            $result['data']['sellTransactionValue'] = bcdiv($bittrexXRPRate * $bittrexXRPQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            $bittrexXRPRate = bcdiv($bittrexXRPRate * (1 - $settings->bittrexAskBelowHighestBidByPercent / 100), 1, $bittrexBTCDecimals);
            $koinexXRPRate = bcdiv($koinexXRPRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100), 1, $koinexINRDecimals);
            $koinexBTCRate = bcdiv($koinexBTCRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Buy ' . $koinexXRPQuantity . ' units of XRP @ Rs. ' . $koinexXRPRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexXRPQuantity * $koinexXRPRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Sell ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to get a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Sell ' . $bittrexXRPQuantity . ' units of XRP @ BTC ' . $bittrexXRPRate . ' per unit to get a total of BTC ' . bcdiv($bittrexXRPQuantity * $bittrexXRPRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxXRPQuantity * $bittrexXRPRate because the result is after the transaction is not in our control
                $instructions ['XRP_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }


    public function findOpportunityBCCKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexBCCTickerData, $koinexBCCTickerVolumeData, $bittrexTickerResponse)
    {


        $koinexBCCDecimals = 3;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexBCCDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexBCCTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexBCCTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexBCCTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexBCCTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexBCCTickerData) > 0 && count($koinexBTCTickerData) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexBCCTickerData->updated_at);

            $koinexBCCRate = bcdiv($koinexBCCTickerData->bid, 1, $koinexINRDecimals);
            $koinexBCCQuantity = bcdiv($koinexBCCTickerVolumeData->buyVolume, 1, $koinexBCCDecimals);

            $bittrexBCCRate = bcdiv($bittrexTickerResponse->sell[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexBCCQuantity = bcdiv($bittrexTickerResponse->sell[0]->Quantity, 1, $bittrexBCCDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->ask, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->sellVolume, 1, $koinexBTCDecimals);

            $koinexBCCTransactionValueInINR = $koinexBCCRate * $koinexBCCQuantity;
            $bittrexBCCTransactionValueInINR = ($bittrexBCCRate * $bittrexBCCQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $koinexBCCTransactionValueInINR, $bittrexBCCTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $koinexBCCQuantity = $maxTranscationValueInINR / $koinexBCCRate;
            $bittrexBCCQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexBCCRate; // (btc value) / btc rate of bcc = bcc quantity

            $koinexBCCQuantity = $bittrexBCCQuantity = min($koinexBCCQuantity, $bittrexBCCQuantity);
            $koinexBTCQuantity = $bittrexBCCRate * $bittrexBCCQuantity;

            $koinexBCCQuantity = bcdiv($koinexBCCQuantity, 1, $koinexBCCDecimals);
            $bittrexBCCQuantity = bcdiv($bittrexBCCQuantity, 1, $koinexBCCDecimals); // $koinexBCCDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $BCC_BTC_Bittrex_Rate_In_INR = $bittrexBCCRate * $koinexBTCRate;
            $arbitrageReturns = ($koinexBCCRate - $BCC_BTC_Bittrex_Rate_In_INR) / $BCC_BTC_Bittrex_Rate_In_INR * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);

            $result['BCCPerDollarRate'] = bcdiv($koinexBCCRate/($this->BTCToUSDRate*$bittrexBCCRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['INR-BCC']['Rate'] = $koinexBCCRate;
            $result['data']['INR-BCC']['Quantity'] = $koinexBCCQuantity;
            $result['data']['INR-BCC']['Amount'] = bcdiv($koinexBCCRate * $koinexBCCQuantity, 1, $koinexINRDecimals);

            $result['data']['BTC-BCC']['Rate'] = $bittrexBCCRate;
            $result['data']['BTC-BCC']['Quantity'] = $bittrexBCCQuantity;
            $result['data']['BTC-BCC']['Amount'] = bcdiv($bittrexBCCRate * $bittrexBCCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv($koinexBTCRate*$koinexBTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['sellTransactionValue'] = bcdiv($koinexBCCRate * $koinexBCCQuantity, 1, $koinexINRDecimals);
            $result['data']['buyTransactionValue'] = bcdiv($bittrexBCCRate * $bittrexBCCQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            $koinexBCCRate = bcdiv($koinexBCCRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);
            $bittrexBCCRate = $bittrexBCCRate * (1 + $settings->bittrexBidAboveLowestAskByPercent / 100);
            $koinexBTCRate = $koinexBTCRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Sell ' . $koinexBCCQuantity . ' units of BCC @ Rs. ' . $koinexBCCRate . ' per unit to get a total of Rs. ' . bcdiv($koinexBCCQuantity * $koinexBCCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Buy ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Buy ' . $bittrexBCCQuantity . ' units of BCC @ BTC ' . $bittrexBCCRate . ' per unit to spend a total of BTC ' . bcdiv($bittrexBCCQuantity * $bittrexBCCRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxBCCQuantity * $bittrexBCCRate because the result is after the transaction is not in our control
                $instructions ['BCC_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }


    public function findOpportunityETHKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexETHTickerData, $koinexETHTickerVolumeData, $bittrexTickerResponse)
    {
        $koinexETHDecimals = 3;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexETHDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexETHTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexETHTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexETHTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexETHTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexETHTickerData) > 0 && count($koinexBTCTickerData) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexETHTickerData->updated_at);

            $koinexETHRate = bcdiv($koinexETHTickerData->bid, 1, $koinexINRDecimals);
            $koinexETHQuantity = bcdiv($koinexETHTickerVolumeData->buyVolume, 1, $koinexETHDecimals);

            $bittrexETHRate = bcdiv($bittrexTickerResponse->sell[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexETHQuantity = bcdiv($bittrexTickerResponse->sell[0]->Quantity, 1, $bittrexETHDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->ask, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->sellVolume, 1, $koinexBTCDecimals);

            $koinexETHTransactionValueInINR = $koinexETHRate * $koinexETHQuantity;
            $bittrexETHTransactionValueInINR = ($bittrexETHRate * $bittrexETHQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $koinexETHTransactionValueInINR, $bittrexETHTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $koinexETHQuantity = $maxTranscationValueInINR / $koinexETHRate;
            $bittrexETHQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexETHRate; // (btc value) / btc rate of ETH = ETH quantity

            $koinexETHQuantity = $bittrexETHQuantity = min($koinexETHQuantity, $bittrexETHQuantity);

            $koinexBTCQuantity = $bittrexETHRate * $bittrexETHQuantity;

            $koinexETHQuantity = bcdiv($koinexETHQuantity, 1, $koinexETHDecimals);
            $bittrexETHQuantity = bcdiv($bittrexETHQuantity, 1, $koinexETHDecimals); // $koinexETHDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $ETH_BTC_Bittrex_Rate_In_INR = $bittrexETHRate * $koinexBTCRate;
            $arbitrageReturns = ($koinexETHRate - $ETH_BTC_Bittrex_Rate_In_INR) / $ETH_BTC_Bittrex_Rate_In_INR * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);

            $result['ETHPerDollarRate'] = bcdiv($koinexETHRate/($this->BTCToUSDRate*$bittrexETHRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['INR-ETH']['Rate'] = $koinexETHRate;
            $result['data']['INR-ETH']['Quantity'] = $koinexETHQuantity;
            $result['data']['INR-ETH']['Amount'] = bcdiv($koinexETHRate * $koinexETHQuantity, 1, $koinexINRDecimals);

            $result['data']['BTC-ETH']['Rate'] = $bittrexETHRate;
            $result['data']['BTC-ETH']['Quantity'] = $bittrexETHQuantity;
            $result['data']['BTC-ETH']['Amount'] = bcdiv($bittrexETHRate * $bittrexETHQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv($koinexBTCRate*$koinexBTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['sellTransactionValue'] = bcdiv($koinexETHRate * $koinexETHQuantity, 1, $koinexINRDecimals);
            $result['data']['buyTransactionValue'] = bcdiv($bittrexETHRate * $bittrexETHQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            $koinexETHRate = bcdiv($koinexETHRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);
            $bittrexETHRate = $bittrexETHRate * (1 + $settings->bittrexBidAboveLowestAskByPercent / 100);
            $koinexBTCRate = $koinexBTCRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Sell ' . $koinexETHQuantity . ' units of ETH @ Rs. ' . $koinexETHRate . ' per unit to get a total of Rs. ' . bcdiv($koinexETHQuantity * $koinexETHRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Buy ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Buy ' . $bittrexETHQuantity . ' units of ETH @ BTC ' . $bittrexETHRate . ' per unit to spend a total of BTC ' . bcdiv($bittrexETHQuantity * $bittrexETHRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxETHQuantity * $bittrexETHRate because the result is after the transaction is not in our control
                $instructions ['ETH_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }


    public function findOpportunityLTCKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexLTCTickerData, $koinexLTCTickerVolumeData, $bittrexTickerResponse)
    {

        $koinexLTCDecimals = 3;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexLTCDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexLTCTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexLTCTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexLTCTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexLTCTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexLTCTickerData) > 0 && count($koinexBTCTickerData) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexLTCTickerData->updated_at);

            $koinexLTCRate = bcdiv($koinexLTCTickerData->bid, 1, $koinexINRDecimals);
            $koinexLTCQuantity = bcdiv($koinexLTCTickerVolumeData->buyVolume, 1, $koinexLTCDecimals);

            $bittrexLTCRate = bcdiv($bittrexTickerResponse->sell[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexLTCQuantity = bcdiv($bittrexTickerResponse->sell[0]->Quantity, 1, $bittrexLTCDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->ask, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->sellVolume, 1, $koinexBTCDecimals);

            $koinexLTCTransactionValueInINR = $koinexLTCRate * $koinexLTCQuantity;
            $bittrexLTCTransactionValueInINR = ($bittrexLTCRate * $bittrexLTCQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $koinexLTCTransactionValueInINR, $bittrexLTCTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $koinexLTCQuantity = $maxTranscationValueInINR / $koinexLTCRate;
            $bittrexLTCQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexLTCRate; // (btc value) / btc rate of LTC = LTC quantity

            $koinexLTCQuantity = $bittrexLTCQuantity = min($koinexLTCQuantity, $bittrexLTCQuantity);

            $koinexBTCQuantity = $bittrexLTCRate * $bittrexLTCQuantity;

            $koinexLTCQuantity = bcdiv($koinexLTCQuantity, 1, $koinexLTCDecimals);
            $bittrexLTCQuantity = bcdiv($bittrexLTCQuantity, 1, $koinexLTCDecimals); // $koinexLTCDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $LTC_BTC_Bittrex_Rate_In_INR = $bittrexLTCRate * $koinexBTCRate;
            $arbitrageReturns = ($koinexLTCRate - $LTC_BTC_Bittrex_Rate_In_INR) / $LTC_BTC_Bittrex_Rate_In_INR * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);

            $result['LTCPerDollarRate'] = bcdiv($koinexLTCRate/($this->BTCToUSDRate*$bittrexLTCRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['INR-LTC']['Rate'] = $koinexLTCRate;
            $result['data']['INR-LTC']['Quantity'] = $koinexLTCQuantity;
            $result['data']['INR-LTC']['Amount'] = bcdiv($koinexLTCRate * $koinexLTCQuantity, 1, $koinexINRDecimals);

            $result['data']['BTC-LTC']['Rate'] = $bittrexLTCRate;
            $result['data']['BTC-LTC']['Quantity'] = $bittrexLTCQuantity;
            $result['data']['BTC-LTC']['Amount'] = bcdiv($bittrexLTCRate * $bittrexLTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv($koinexBTCRate*$koinexBTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['sellTransactionValue'] = bcdiv($koinexLTCRate * $koinexLTCQuantity, 1, $koinexINRDecimals);
            $result['data']['buyTransactionValue'] = bcdiv($bittrexLTCRate * $bittrexLTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            $koinexLTCRate = bcdiv($koinexLTCRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);
            $bittrexLTCRate = $bittrexLTCRate * (1 + $settings->bittrexBidAboveLowestAskByPercent / 100);
            $koinexBTCRate = $koinexBTCRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }


            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Sell ' . $koinexLTCQuantity . ' units of LTC @ Rs. ' . $koinexLTCRate . ' per unit to get a total of Rs. ' . bcdiv($koinexLTCQuantity * $koinexLTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Buy ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Buy ' . $bittrexLTCQuantity . ' units of LTC @ BTC ' . $bittrexLTCRate . ' per unit to spend a total of BTC ' . bcdiv($bittrexLTCQuantity * $bittrexLTCRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxLTCQuantity * $bittrexLTCRate because the result is after the transaction is not in our control
                $instructions ['LTC_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }

    public function findOpportunityXRPKoinexUpBittrexDown($settings, $koinexBTCTickerData, $koinexBTCTickerVolumeData, $koinexXRPTickerData, $koinexXRPTickerVolumeData, $bittrexTickerResponse)
    {
        $koinexXRPDecimals = 3;
        $koinexBTCDecimals = 4;
        $koinexINRDecimals = 2;
        $bittrexBTCDecimals = 8;
        $bittrexXRPDecimals = 8;

        $result = [];
        $result['isArbitrageOpportunity'] = false;

        $minimumTradeSize = $settings->minimumTradeSize;
        $maximumTradeSize = $settings->maximumTradeSize;
        $minimumGrossPercentGain = $settings->minimumGrossPercentGain;

        $lastRun = $koinexXRPTickerData->updated_at;
        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

        $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsPriceUpdates);
        $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

        if ($timeDifference < 0) {
            $message = 'Koinex ticker price data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexXRPTickerData->updated_at->format('d-m-Y G:i:s');
            $result['message'] = $message;
            return $result;
        }


        if ($this->checkVolumeLastUpdatedTimestamp) {
            $lastRun = $koinexXRPTickerVolumeData->updated_at;
            $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
            $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

            $fifteenSecondsBack = Carbon::now()->subSeconds($this->delayInSecondsVolumeUpdates);
            $timeDifference = $fifteenSecondsBack->diffInSeconds($lastRun, false);

            if ($timeDifference < 0) {
                $message = 'Koinex ticker volume data is not latest. Ticker data request timestamp is ' . Carbon::now()->format('d-m-Y G:i:s') . ' . Last updated timestamp is ' . $koinexXRPTickerVolumeData->updated_at->format('d-m-Y G:i:s');
                $result['message'] = $message;
                return $result;
            }
        }


        if (count($koinexXRPTickerData) > 0 && count($koinexBTCTickerData) > 0) {

            $result['data']['timestamp'] = Carbon::now()->diffInSeconds($koinexXRPTickerData->updated_at);

            $koinexXRPRate = bcdiv($koinexXRPTickerData->bid, 1, $koinexINRDecimals);
            $koinexXRPQuantity = bcdiv($koinexXRPTickerVolumeData->buyVolume, 1, $koinexXRPDecimals);

            $bittrexXRPRate = bcdiv($bittrexTickerResponse->sell[0]->Rate, 1, $bittrexBTCDecimals);
            $bittrexXRPQuantity = bcdiv($bittrexTickerResponse->sell[0]->Quantity, 1, $bittrexXRPDecimals);

            $koinexBTCRate = bcdiv($koinexBTCTickerData->ask, 1, $koinexINRDecimals);
            $koinexBTCQuantity = bcdiv($koinexBTCTickerVolumeData->sellVolume, 1, $koinexBTCDecimals);

            $koinexXRPTransactionValueInINR = $koinexXRPRate * $koinexXRPQuantity;
            $bittrexXRPTransactionValueInINR = ($bittrexXRPRate * $bittrexXRPQuantity) * $koinexBTCRate; //(btc value) * btc rate in INR
            $koinexBTCTransactionValueInINR = $koinexBTCRate * $koinexBTCQuantity;

            $maxTranscationValueInINR = min($maximumTradeSize, $koinexXRPTransactionValueInINR, $bittrexXRPTransactionValueInINR, $koinexBTCTransactionValueInINR);

            $koinexXRPQuantity = $maxTranscationValueInINR / $koinexXRPRate;
            $bittrexXRPQuantity = ($maxTranscationValueInINR / $koinexBTCRate) / $bittrexXRPRate; // (btc value) / btc rate of XRP = XRP quantity

            $koinexXRPQuantity = $bittrexXRPQuantity = min($koinexXRPQuantity, $bittrexXRPQuantity);

            $koinexBTCQuantity = $bittrexXRPRate * $bittrexXRPQuantity;

            $koinexXRPQuantity = bcdiv($koinexXRPQuantity, 1, $koinexXRPDecimals);
            $bittrexXRPQuantity = bcdiv($bittrexXRPQuantity, 1, $koinexXRPDecimals); // $koinexXRPDecimals => To make sure that you buy and sell same quantity on each exchange
            $koinexBTCQuantity = bcdiv($koinexBTCQuantity, 1, $koinexBTCDecimals);

            $XRP_BTC_Bittrex_Rate_In_INR = $bittrexXRPRate * $koinexBTCRate;
            $arbitrageReturns = ($koinexXRPRate - $XRP_BTC_Bittrex_Rate_In_INR) / $XRP_BTC_Bittrex_Rate_In_INR * 100;
            $result['data']['returns'] = bcdiv($arbitrageReturns, 1, 2);

            $result['XRPPerDollarRate'] = bcdiv($koinexXRPRate/($this->BTCToUSDRate*$bittrexXRPRate), 1, 2);
            $result['BTCPerDollarRate'] = bcdiv($koinexBTCRate/($this->BTCToUSDRate), 1, 2);
            $result['data']['INR-XRP']['Rate'] = $koinexXRPRate;
            $result['data']['INR-XRP']['Quantity'] = $koinexXRPQuantity;
            $result['data']['INR-XRP']['Amount'] = bcdiv($koinexXRPRate * $koinexXRPQuantity, 1, $koinexINRDecimals);

            $result['data']['BTC-XRP']['Rate'] = $bittrexXRPRate;
            $result['data']['BTC-XRP']['Quantity'] = $bittrexXRPQuantity;
            $result['data']['BTC-XRP']['Amount'] = bcdiv($bittrexXRPRate * $bittrexXRPQuantity, 1, $bittrexBTCDecimals);

            $result['data']['INR-BTC']['Rate'] = $koinexBTCRate;
            $result['data']['INR-BTC']['Quantity'] = $koinexBTCQuantity;
            $result['data']['INR-BTC']['Amount'] = bcdiv($koinexBTCRate*$koinexBTCQuantity, 1, $bittrexBTCDecimals);

            $result['data']['sellTransactionValue'] = bcdiv($koinexXRPRate * $koinexXRPQuantity, 1, $koinexINRDecimals);
            $result['data']['buyTransactionValue'] = bcdiv($bittrexXRPRate * $bittrexXRPQuantity * $koinexBTCRate, 1, $koinexINRDecimals);

            $koinexXRPRate = bcdiv($koinexXRPRate * (1 - $settings->koinexAskBelowHighestBidByPercent / 100), 1, $koinexINRDecimals);
            $bittrexXRPRate = $bittrexXRPRate * (1 + $settings->bittrexBidAboveLowestAskByPercent / 100);
            $koinexBTCRate = $koinexBTCRate * (1 + $settings->koinexBidAboveLowestAskByPercent / 100);

            if ($arbitrageReturns < $minimumGrossPercentGain) {
                $message = 'No arbitrage opportunity.';
                $result['message'] = $message;
                return $result;
            }

            if ($maxTranscationValueInINR < $minimumTradeSize) {
                $message = 'Minimum trade size criteria is not met.';
                $result['message'] = $message;
                return $result;
            }

            $instructions = [];

            if ($arbitrageReturns >= $minimumGrossPercentGain) {
                $explanation = [];
                $result['isArbitrageOpportunity'] = true;
                $explanation['firstTransaction'] = 'Sell ' . $koinexXRPQuantity . ' units of XRP @ Rs. ' . $koinexXRPRate . ' per unit to get a total of Rs. ' . bcdiv($koinexXRPQuantity * $koinexXRPRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['secondTransaction'] = ' Buy ' . $koinexBTCQuantity . ' units of BTC @ Rs. ' . $koinexBTCRate . ' per unit to spend a total of Rs. ' . bcdiv($koinexBTCQuantity * $koinexBTCRate, 1, $koinexINRDecimals) . ' in Koinex';
                $explanation['thirdTransaction'] = ' Buy ' . $bittrexXRPQuantity . ' units of XRP @ BTC ' . $bittrexXRPRate . ' per unit to spend a total of BTC ' . bcdiv($bittrexXRPQuantity * $bittrexXRPRate, 1, $bittrexBTCDecimals) . ' in Bittrex'; // Not rounding $maxXRPQuantity * $bittrexXRPRate because the result is after the transaction is not in our control
                $instructions ['XRP_BittrexUp_KoinexDown'] = $explanation;
            }
            $result['instructions'] = $instructions;

            $message = '';
            $result['message'] = $message;
            return $result;

        } else {
            $message = 'DB is not sending Koinex ticker data, please try after sometime';
            $result['message'] = $message;
            return $result;
        }

    }

}