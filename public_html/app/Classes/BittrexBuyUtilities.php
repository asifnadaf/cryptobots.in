<?php

namespace App\Classes;

use Log;
use App\Classes\BittrexAccountUtilities;
use App\Models\BotSettingsModel;

class BittrexBuyUtilities
{

    var $apiKey = null;
    var $secretKey = null;
    var $bittrexAPIs = null;
    var $BTCPrefix = 'BTC-';
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function invest($clientData, $toBeInvestedCoins)
    {

        $investedMarkets = [];
        $BTCAmountToBeInvested = $this->getBTCAmountToBeInvested($clientData);

        if ($BTCAmountToBeInvested < $this->bittrexMinimumInvestmentLimits * 1.1) {
            $marketList = "";
            foreach ($toBeInvestedCoins as $row) {
                $marketList = $marketList . $row->MarketName . ',' ;
            }
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' Minimum investment conditions not satisfied clientData->fullName: ' . $clientData->fullName.' $BTCAmountToBeInvested: '.$BTCAmountToBeInvested.' $marketList: '.$marketList);
            return $investedMarkets;
        }

        try {
            foreach ($toBeInvestedCoins as $row) {
                $marketName = $row->MarketName;
                $rate = $row->Ask;
                $quantity = $BTCAmountToBeInvested / $rate * 0.99;

                $bittrexMarketUtilities = new BittrexMarketUtilities($clientData->apiKey, $clientData->secretKey);
                $bittrexMarketUtilities->buyLimit($marketName,$quantity,$rate);

                $row->quantity = $quantity;
                $row->btcQuantity = $BTCAmountToBeInvested;
                $investedMarkets [] = $row;
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $investedMarkets;

    }


    public function getBTCAmountToBeInvested($clientData)
    {
        $BTCAmountToBeInvested = 0;
        try {

            $botSettingRow = BotSettingsModel::first();
//            $reserveFundsPercentage = $botSettingRow->reserveFundsPercentage;
            $maximumNumberOfDiversification = $botSettingRow->maximumNumberOfDiversification;
            $maximumNumberOfDiversification = 1/$maximumNumberOfDiversification;

            $bittrexAccountUtilities= new BittrexAccountUtilities($clientData->apiKey, $clientData->secretKey);
            $accountBalance = $bittrexAccountUtilities->getUSDTAndBTCBalance();
            $totalBalanceIn_BTC = $accountBalance['totalBalanceIn_BTC'] ;
//            $totalBalanceIn_BTC = $totalBalanceIn_BTC * (1-$reserveFundsPercentage/100); //Make sure that only 50% of total funds are invested through bot.
            $btcBalance = $bittrexAccountUtilities->getBalance('BTC');
            $btcBalance = $btcBalance->Available;

            $totalBalanceIn_BTC = $totalBalanceIn_BTC * $maximumNumberOfDiversification; // Diversification -> Only small percentage amount is invested per altcoin
            $BTCAmountToBeInvested = min($btcBalance, $totalBalanceIn_BTC);
            $BTCAmountToBeInvested = $BTCAmountToBeInvested * .95;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
        }
        return $BTCAmountToBeInvested;
    }

}