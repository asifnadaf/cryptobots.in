<?php

namespace App\Classes\BittrexBots\Support;

use App\Classes\BittrexMarketUtilities;
use App\Classes\BittrexAccountUtilities;
use App\Classes\BittrexBuyUtilities;
use App\Classes\BittrexUSDTMarketUtilities;
use App\Models\MarketOddsModel;
use App\Models\BotRunningStatusModel;
use App\Models\BotSettingsModel;
use App\Models\ClientsListModel;

use Log;
use Mail;
use Carbon\Carbon;
use Mockery\Exception;

class BuyAtSupportBot
{
    var $className = null;
    var $BTCPrefix = 'BTC-';
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function buyAtSupportPriceBot()
    {

        $startTime = Carbon::now();

        $botSettingRow = BotSettingsModel::first();
        $minimumVolumeOfBaseCurrencyBTC = $botSettingRow->minimumVolumeOfBaseCurrencyBTC;
        $supportAndEqualOddsRatio = $botSettingRow->supportAndEqualOddsRatio;

        try {

            $USDTBTCPrices = MarketOddsModel::whereRaw('Ask <=supportPrice')->where('marketName', '=', 'USDT-BTC')->get();

            if (count($USDTBTCPrices) > 0) {
                $bittrexUSDTMarketUtilities = new BittrexUSDTMarketUtilities();
                $bittrexUSDTMarketUtilities->buyBTCUsingTether();

                $belowSupportPriceList = MarketOddsModel::where('isBuyingPaused', '=', 'No')->whereRaw('Ask <=supportPrice')->where('supportAndEqualOddsRatio', '>=', $supportAndEqualOddsRatio)->where('BaseVolume', '>=', $minimumVolumeOfBaseCurrencyBTC)->orderBy('supportNLastPercentageDifference', 'asc')->get();
                $this->removeToBeDelistedMarkets($belowSupportPriceList);
                if (count($belowSupportPriceList) > 0) {

                    $clientsList = ClientsListModel::all();
                    foreach ($clientsList as $row) {

                        $apiKey = $row->apiKey;
                        $secretKey = $row->secretKey;

                        $pauseTrading = $row->pauseTrading;
                        if (strcasecmp($pauseTrading, 'Yes') == 0) {
                            continue;
                        }

                        $bittrexAccountUtilities = new BittrexAccountUtilities($apiKey, $secretKey);
                        $alreadyInvestedCoins = $bittrexAccountUtilities->getAltcoinsBalance();
                        $toBeInvestedCoins = $this->toBeInvestedList($belowSupportPriceList, $alreadyInvestedCoins);

                        $newlyInvestedCoins = [];
                        if (count($toBeInvestedCoins) > 0) {
                            $bittrexBuyUtilities = new BittrexBuyUtilities();
                            $newlyInvestedCoins = $bittrexBuyUtilities->invest($row, $toBeInvestedCoins);
                        }

                        if (count($newlyInvestedCoins) > 0) {
                            $this->createLogRecordsOfBuying($row, $newlyInvestedCoins);
                        }
                    }
                }
            }

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

    public function removeToBeDelistedMarkets($belowSupportPriceList)
    {
        $finalList = [];

        $bittrexUtilities = new BittrexMarketUtilities(null, null);
        $delistingMarketsList = $bittrexUtilities->delistingMarkets();

        if (count($delistingMarketsList) > 0) {
            try {
                foreach ($belowSupportPriceList as $row) {
                    $isMarketBeingDelisted = false;
                    foreach ($delistingMarketsList as $delistingMarket) {
                        if (strcasecmp($row->MarketName, $delistingMarket['MarketName']) == 0) {
                            $isMarketBeingDelisted = true;
                            break;
                        }
                    }
                    if (!$isMarketBeingDelisted) {
                        $finalList[] = $row;
                    }
                }


            } catch (Exception $exception) {
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
            }
        } else {
            $finalList = $belowSupportPriceList;
        }

        return $finalList;

    }

    public function toBeInvestedList($belowSupportPriceList, $alreadyInvestedCoins)
    {
        $intermediateList = [];
        $finalList = [];
        try {
            foreach ($belowSupportPriceList as $row) {
                $isAlreadyInvested = false;
                foreach ($alreadyInvestedCoins as $alreadyInvestedCoin) {
                    if (strcasecmp($row->MarketName, $this->BTCPrefix . $alreadyInvestedCoin->Currency) == 0) {
                        $isAlreadyInvested = true;
                        break;
                    }
                }
                if (!$isAlreadyInvested) {
                    $finalList[] = $row;
                }
            }


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $finalList;

    }

//    public function invest($clientData,$toBeInvestedList)
//    {
//
//
//        $investedMarkets = [];
//        $BTCAmountToBeInvested = $this->getBTCAmountToBeInvested($clientData);
//
//        if ($BTCAmountToBeInvested < $this->bittrexMinimumInvestmentLimits * 1.1) {
//            return $investedMarkets;
//        }
//
//        try {
//            foreach ($toBeInvestedList as $row) {
//                $marketName = $row->MarketName;
//                $rate = $row->Ask;
//                $quantity = $BTCAmountToBeInvested / $rate;
//
//                $bittrexMarketUtilities = new BittrexMarketUtilities($clientData->apiKey, $clientData->secretKey);
//                $bittrexMarketUtilities->buyLimit($marketName,$quantity,$rate);
//
//                $row->quantity = $quantity;
//                $row->btcQuantity = $BTCAmountToBeInvested;
//                $investedMarkets [] = $row;
//            }
//
//        } catch (Exception $exception) {
//            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
//        }
//        return $investedMarkets;
//
//    }


//    public function getBTCAmountToBeInvested($clientData)
//    {
//        $BTCAmountToBeInvested = 0;
//        try {
//
//            $bittrexAccountUtilities= new BittrexAccountUtilities($clientData->apiKey, $clientData->secretKey);
//            $accountBalance = $bittrexAccountUtilities->getUSDTAndBTCBalance();
//            $totalBalanceIn_BTC = $accountBalance['totalBalanceIn_BTC'] ;
//
//            $btcBalance = $bittrexAccountUtilities->getBalance('BTC');
//            $btcBalance = $btcBalance->Available;
//
//            $totalBalanceIn_BTC = $totalBalanceIn_BTC * $this->maximumBTCToInvestFactor;
//            $BTCAmountToBeInvested = min($btcBalance, $totalBalanceIn_BTC);
//            $BTCAmountToBeInvested = $BTCAmountToBeInvested * .95;
//
//        } catch (Exception $exception) {
//            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
//        }
//        return $BTCAmountToBeInvested;
//    }


    public function createLogRecordsOfBuying($clientData, $data)
    {

        try {
            foreach ($data as $row) {
                Log::info(get_class($this) . '->' . __FUNCTION__);
                Log::info('PURCHASE DETAILS');
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'Client name ' . $clientData->fullName);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'MarketName ' . $row->MarketName);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'manualSupportPrice ' . $row->manualSupportPrice);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'rate ' . $row->Ask);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'quantity ' . $row->quantity);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'btcQuantity ' . $row->btcQuantity);
                Log::info('');
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

    }

}