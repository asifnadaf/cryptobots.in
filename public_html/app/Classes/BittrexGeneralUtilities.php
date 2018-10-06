<?php

namespace App\Classes;

use Log;
use Carbon\Carbon;
use App\Models\BotRunningStatusModel;
use App\Models\BalanceHistoryModel;
use App\Models\BaseCurrenciesRateModel;

class BittrexGeneralUtilities
{

    var $apiKey = null;
    var $secretKey = null;
    var $bittrexAPIs = null;
    var $BTCPrefix = 'BTC-';

    public function __construct($apiKey = null, $secretKey = null)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);
    }

    public function cancelAllSellLimit()
    {
        try {
            $bittrexUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);
            $openOrders = $bittrexUtilities->getOpenOrders(null);

            foreach ($openOrders as $openOrder) {
                if (strcasecmp($openOrder->OrderType, 'LIMIT_SELL') == 0) {
                    try {

                        $orderUuid = $openOrder->OrderUuid;
                        $bittrexUtilities->cancel($orderUuid);

                    } catch (Exception $exception) {
                        Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'Market Name ' . $openOrder->Exchange);
                        Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception ' . $exception);
                    }
                }
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' General exception ' . $exception);
        }

    }


    public function createAllSellLimit($timesFactor)
    {
        $randomFactor = $this->float_rand(1.0001, 1.0015, 4); // Bid any random number between 0.0025% and 0.05% then the going ask rate at the exchange.
        $timesFactor = $timesFactor * $randomFactor;
        $bittrexMinimumInvestmentSize = 0.00050000;             // Minimum investment amount restrictions from exchange.

        try {

            $bittrexAccountUtilities = new BittrexAccountUtilities($this->apiKey, $this->secretKey);
            $altcoinsBalance = $bittrexAccountUtilities->getAltcoinsBalance();

            foreach ($altcoinsBalance as $balance) {
                try {
                    $marketName = $this->BTCPrefix . $balance->Currency;

                    $bittrexGeneralUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);
                    $ticker = $bittrexGeneralUtilities->getTickerDataFromDB($marketName);
                    $quantity = $balance->Available;
                    $bidRate = $ticker->Bid;
                    if ($quantity * $bidRate >= $bittrexMinimumInvestmentSize) {
                        $rate = $bidRate * $timesFactor;
                        $sellLimitResponse['status'] = $this->bittrexAPIs->sellLimit($marketName, $quantity, (float)$rate);
                    } else {
                        Log::info(get_class($this) . '->' . __FUNCTION__ . ' Sell limit order is not placed because it does not meet minimum investments requirement of exchange $marketName ' . $marketName . ' $quantity ' . $quantity . ' $bidRate ' . $bidRate . ' $quantity * $bidRate ' . $quantity * $bidRate);
                    }

                } catch (Exception $exception) {
                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'Market Name ' . $marketName . ' $quantity ' . $quantity . ' $rate ' . $rate);
                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception ' . $exception);
                }
            }


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' General exception: ' . '   exception ' . $exception);
        }
    }


    public function sellSpecificMarket($clientData, $altcoinsName)
    {

        $bittrexMinimumInvestmentSize = 0.00050000;             // Minimum investment amount restrictions from exchange.
        $randomFactor = 1; // Bid any random number between 0.0025% and 0.05% then the going ask rate at the exchange.
//        $randomFactor = $this->float_rand(1.0001, 1.0015, 4); // Bid any random number between 0.0025% and 0.05% then the going ask rate at the exchange.
        $baseCurrency = 'BTC-';
        $quantity = null;

        $pauseTrading = $clientData->pauseTrading;

        if (strcasecmp($pauseTrading, 'Yes') == 0) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . 'Account trading is paused. Edit clients details to enable it ' . $clientData->fullName);
            return;
        }

        $bittrexAPIs = new BittrexAPIs($this->apiKey, $this->secretKey);

        foreach ($altcoinsName as $row) {

            try {
                $currencyName = $row;
                $marketName = $baseCurrency . $currencyName;

                $alcoinBalance = $bittrexAPIs->getBalance($currencyName);

                $quantity = $alcoinBalance->Balance;
                $ticker = $bittrexAPIs->getTicker($marketName);
                $rate = $ticker->Bid * $randomFactor;

                if ($quantity * $rate >= $bittrexMinimumInvestmentSize) {
                    Log::info(get_class($this) . '->' . __FUNCTION__ . ' Market Name ' . $marketName . ' $quantity ' . $quantity . ' $rate ' . $rate);
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
            } catch (Exception $exception) {
                Log::info(get_class($this) . '->' . __FUNCTION__ . '  ' . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
                Log::info(get_class($this) . '->' . __FUNCTION__ . 'exception: ' . $exception);
            }
        }

    }


    function float_rand($Min, $Max, $round = 0)
    {
        //validate input
        if ($Min > $Max) {
            $min = $Max;
            $max = $Min;
        } else {
            $min = $Min;
            $max = $Max;
        }
        $randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        if ($round > 0)
            $randomfloat = round($randomfloat, $round);

        return $randomfloat;
    }


    public function setBalanceHistory($clientId)
    {

        try {

            $accountBalances = [];
            $totalBalanceIn_BTC = 0;
            $totalBalanceIn_USDT = 0;
            $marketName = null;
            $quantity = null;
            $rate = null;

            $currenciesUtilities = new CurrenciesUtilities($this->apiKey, $this->secretKey);
            $BTC_To_USDT = $currenciesUtilities->getTetherBTCRateFromMarket();

            $currenciesIncludingZeroBalance = $this->bittrexAPIs->getBalances();
            if ($currenciesIncludingZeroBalance != null) {
                $currenciesWithBalance = null;
                foreach ($currenciesIncludingZeroBalance as $row) {
                    if ($row->Balance > 0) {
                        $currenciesWithBalance[] = $row;
                    }
                }

                foreach ($currenciesWithBalance as $row) {

                    $marketName = 'BTC-' . $row->Currency;

                    if (strcasecmp($marketName, 'BTC-BTC') == 0) {
                        $quantity = $row->Balance;
                        $row->altcoinToBTC = $quantity;
                        $row->altcoinToUSDT = $quantity * $BTC_To_USDT;
                        $totalBalanceIn_BTC = $totalBalanceIn_BTC + $quantity;
                        $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                        $accountBalances[] = $row;
                    } elseif (strcasecmp($marketName, 'BTC-USDT') == 0) {
                        $quantity = $row->Balance;
                        $row->altcoinToBTC = $quantity / $BTC_To_USDT;
                        $row->altcoinToUSDT = $quantity;
                        $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                        $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                        $accountBalances[] = $row;
                    } else {
                        $bittrexGeneralUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);
                        $ticker = $bittrexGeneralUtilities->getTickerDataFromDB($marketName);
                        $rate = $ticker->Last;
                        $quantity = $row->Balance;
                        $row->altcoinToBTC = $rate * $quantity;
                        $row->altcoinToUSDT = $rate * $quantity * $BTC_To_USDT;
                        $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                        $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                        $accountBalances[] = $row;
                    }

                }

                $clientBalanceHistory = new BalanceHistoryModel();
                $clientBalanceHistory->clientId = $clientId;
                $clientBalanceHistory->btcBalanceValue = $totalBalanceIn_BTC;
                $clientBalanceHistory->BTCToTetherRate = $BTC_To_USDT;
                $clientBalanceHistory->usdtBalanceValue = $totalBalanceIn_USDT;
                $clientBalanceHistory->created_at = Carbon::now();
                $clientBalanceHistory->updated_at = Carbon::now();
                $clientBalanceHistory->save();

                $className = 'RecordBalanceHistoryBot';
                $botRunningStatusData = BotRunningStatusModel::where('className', '=', $className)->first();
                $botRunningStatusData->dbAffected = 'BalanceHistoryModel';
                $botRunningStatusData->dbLatestUpdatesTimestamp = Carbon::now();
                $botRunningStatusData->save();
            }


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception:' . $exception);
        }

    }


    public function getBalanceHistory($clientId)
    {

        $balanceHistoryData = [];
        $tickers = null;
        $accountBalances = null;
        $totalBalanceIn_BTC = 0;
        $totalBalanceIn_USDT = 0;
        $marketName = null;
        $quantity = null;
        $rate = null;
        $accountError = null;
        try {

            $balanceHistory = BalanceHistoryModel::where('clientId', '=', $clientId)->orderBy('created_at', 'DESC')->get();

            $currenciesUtilities = new CurrenciesUtilities($this->apiKey, $this->secretKey);
            $USDT_To_BTC = $currenciesUtilities->getTetherBTCRateFromMarket();

            $currenciesIncludingZeroBalance = $this->bittrexAPIs->getBalances();

            $currenciesWithBalance = [];
            foreach ($currenciesIncludingZeroBalance as $row) {
                if ($row->Balance > 0) {
                    $currenciesWithBalance[] = $row;
                }
            }

            foreach ($currenciesWithBalance as $row) {

                $marketName = $this->BTCPrefix . $row->Currency;

                if (strcasecmp($marketName, 'BTC-BTC') == 0) {
                    $quantity = $row->Balance;
                    $row->altcoinToBTC = $quantity;
                    $row->altcoinToUSDT = $quantity * $USDT_To_BTC;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                    $accountBalances[] = $row;
                } elseif (strcasecmp($marketName, 'BTC-USDT') == 0) {
                    $quantity = $row->Balance;
                    $row->altcoinToBTC = $quantity / $USDT_To_BTC;
                    $row->altcoinToUSDT = $quantity;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                    $accountBalances[] = $row;
                } else {
                    $bittrexUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);
                    $ticker = $bittrexUtilities->getTickerDataFromDB($marketName);
                    $rate = $ticker->Last;
                    $quantity = $row->Balance;
                    $row->altcoinToBTC = $rate * $quantity;
                    $row->altcoinToUSDT = $rate * $quantity * $USDT_To_BTC;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                    $accountBalances[] = $row;
                }

            }
            $bittrexAccountUtilities = new BittrexAccountUtilities($this->apiKey, $this->secretKey);
            $accountBalance = $bittrexAccountUtilities->getUSDTAndBTCBalance();

            $balanceHistoryData['balanceHistory'] = $balanceHistory;
            $balanceHistoryData['totalBalanceIn_BTC'] = $totalBalanceIn_BTC;
            $balanceHistoryData['totalBalanceIn_USDT'] = $totalBalanceIn_USDT;
            $balanceHistoryData['accountBalance'] = $accountBalance;
            $balanceHistoryData['accountError'] = null;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . $marketName . ' $quantity ' . $quantity . ' rate ' . $rate);
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            $accountError = 'An exception has occurred, please contact support team for help: ' . $exception->getMessage() . ' - ' . $marketName;
            $balanceHistoryData['accountError'] = $accountError;
        }
        return $balanceHistoryData;
    }


    public function getExchangeOpenOrders()
    {
        $bittrexMarketUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);

        $currenciesUtilities = new CurrenciesUtilities($this->apiKey, $this->secretKey);
        $USDT_To_BTC = $currenciesUtilities->getTetherBTCRateFromMarket();

        $USDT_To_BTC_24HoursBackPrice = $this->get24HoursBackUSDTPrice();

        $openOrders = [];

        try {
            $openOrdersRawData = $bittrexMarketUtilities->getOpenOrders(null);
            $openOrdersRawData = array_reverse($openOrdersRawData);

            $bittrexAccountUtilities = new BittrexAccountUtilities($this->apiKey, $this->secretKey);
            $completedOrders = $bittrexAccountUtilities->getorderhistory();

            $marketSummaries = $bittrexMarketUtilities->getMarketSummariesFromDB();
            $openOrders = [];

            foreach ($openOrdersRawData as $openOrder) {
                $marketName = $openOrder->Exchange;

                if (strpos($marketName, $this->BTCPrefix) !== false) {

                    $ticker = $marketSummary = $this->getMarketSummary($marketSummaries, $marketName);

                    $openOrder->currentBTCRateInUSDT = $USDT_To_BTC;

                    $purchasePriceDetails = $this->getPurchasePriceDetails($completedOrders, $marketName);
                    if (count($purchasePriceDetails) > 0) {

                        $openOrder->BTCRateInUSDTAtTheTimeOfPurchase = $purchasePriceDetails['BTCRateInUSDTAtTheTimeOfPurchase'];

                        if ($openOrder->BTCRateInUSDTAtTheTimeOfPurchase > 0) {
                            $openOrder->percentChangeUSDTToBTC = ($USDT_To_BTC - $openOrder->BTCRateInUSDTAtTheTimeOfPurchase) / $openOrder->BTCRateInUSDTAtTheTimeOfPurchase * 100;
                        } else {
                            $openOrder->percentChangeUSDTToBTC = 0;
                        }

                        $openOrder->investedAmountInUSDT = $purchasePriceDetails['purchasePriceInUSDT'] * $openOrder->QuantityRemaining;
                        $openOrder->currentValueInUSDT = $ticker->Last * $USDT_To_BTC * $openOrder->QuantityRemaining;

                        $openOrder->purchasePricePerUnitInBTC = $purchasePriceDetails['purchasePriceInBTC'];
                        $openOrder->currentPricePerUnitInBTC = $ticker->Last;
                        $openOrder->investedAmountInBTC = $purchasePriceDetails['purchasePriceInBTC'] * $openOrder->QuantityRemaining;
                        $openOrder->currentValueInBTC = $ticker->Last * $openOrder->QuantityRemaining;

                        $openOrder->purchaseTimestamp = $purchasePriceDetails['purchaseTimestamp'];

                        if ($openOrder->investedAmountInUSDT > 0) {
                            $percentChangeInUSDT = ($ticker->Last * $USDT_To_BTC - $purchasePriceDetails['purchasePriceInUSDT']) / $purchasePriceDetails['purchasePriceInUSDT'] * 100;
                            $openOrder->percentChangeInUSDT = $percentChangeInUSDT;
                        } else {
                            $openOrder->percentChangeInUSDT = 0;
                        }

                        $percentChangeInBTC = ($ticker->Last - $purchasePriceDetails['purchasePriceInBTC']) / $purchasePriceDetails['purchasePriceInBTC'] * 100;
                        $openOrder->percentChangeInBTC = $percentChangeInBTC;

                    } else {
                        $openOrder->purchasePricePerUnitInBTC = 0.00;
                        $openOrder->currentPricePerUnitInBTC = 0.00;

                        $openOrder->BTCRateInUSDTAtTheTimeOfPurchase = 0.00;
                        $openOrder->percentChangeUSDTToBTC = 0.00;

                        $openOrder->investedAmountInUSDT = 0.00;
                        $openOrder->currentValueInUSDT = 0.00;

                        $openOrder->investedAmountInBTC = 0.00;
                        $openOrder->currentValueInBTC = 0.00;

                        $openOrder->percentChangeInUSDT = 0.00;
                        $openOrder->percentChangeInBTC = 0.00;
                    }

                    if ($marketSummary->PrevDay != 0 && $USDT_To_BTC_24HoursBackPrice != 0) {

                        $last24HoursPercentChangeInUSDT = ($ticker->Last * $USDT_To_BTC - $marketSummary->PrevDay * $USDT_To_BTC_24HoursBackPrice) / ($marketSummary->PrevDay * $USDT_To_BTC_24HoursBackPrice) * 100;
                        $openOrder->last24HoursPercentChangeInUSDT = $last24HoursPercentChangeInUSDT;

                        $last24HoursPercentChangeInBTC = ($ticker->Last - $marketSummary->PrevDay) / $marketSummary->PrevDay * 100;
                        $openOrder->last24HoursPercentChangeInBTC = $last24HoursPercentChangeInBTC;
                    } else {

                        $last24HoursPercentChangeInUSDT = 0.00;
                        $openOrder->last24HoursPercentChangeInUSDT = $last24HoursPercentChangeInUSDT;

                        $last24HoursPercentChangeInBTC = 0.00;
                        $openOrder->last24HoursPercentChangeInBTC = $last24HoursPercentChangeInBTC;
                    }

                    $openOrders[] = $openOrder;

                }

            }

            usort($openOrders, function ($a, $b) {
                return $b->percentChangeInBTC <=> $a->percentChangeInBTC;
            });


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' orders exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
        }
        return $openOrders;
    }

    public function get24HoursBackUSDTPrice()
    {

        $twentyFourHoursBackTimestamp = Carbon::now();;
        $twentyFourHoursBackTimestamp = $twentyFourHoursBackTimestamp->subHours(24);
        $twentyFourHoursBackMinusOneMinuteTimestamp = $twentyFourHoursBackTimestamp;
        $twentyFourHoursBackTimestamp = $twentyFourHoursBackTimestamp->toDateTimeString();

        $twentyFourHoursBackMinusOneMinuteTimestamp = $twentyFourHoursBackMinusOneMinuteTimestamp->subMinute(1);
        $twentyFourHoursBackMinusOneMinuteTimestamp = $twentyFourHoursBackMinusOneMinuteTimestamp->toDateTimeString();

        $baseCurrencyRow = BaseCurrenciesRateModel::where('created_at', '<=', $twentyFourHoursBackTimestamp)->where('created_at', '>=', $twentyFourHoursBackMinusOneMinuteTimestamp)->first();
        if (count($baseCurrencyRow) > 0) {
            $Last24HoursPriceInUSDT = $baseCurrencyRow->Tether_BTC_Rate;

        } else {
            $Last24HoursPriceInUSDT = 0;
        }

        return $Last24HoursPriceInUSDT;
    }


    public function getPurchasePriceDetails($completedOrders, $marketName)
    {
        $purchasePriceDetails = [];
        foreach ($completedOrders as $row) {
            if (strcasecmp($row->Exchange, $marketName) == 0) {
                if (strcasecmp($row->OrderType, 'LIMIT_BUY') == 0) {
                    $purchasePriceDetails['marketName'] = $marketName;
                    $purchasePriceDetails['purchasePriceInBTC'] = $row->PricePerUnit;

                    $purchaseTimestamp = Carbon::parse($row->Closed, 'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y G:i:s');
                    $purchaseTimestamp = Carbon::createFromFormat('d-m-Y G:i:s', $purchaseTimestamp);
                    $purchaseTimestamp->toDateTimeString();

                    $aMinuteBeforePurchase = Carbon::parse($row->Closed, 'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y G:i:s');
                    $aMinuteBeforePurchase = Carbon::createFromFormat('d-m-Y G:i:s', $aMinuteBeforePurchase);
                    $aMinuteBeforePurchase = $aMinuteBeforePurchase->subSeconds(65);
//                    $aMinuteBeforePurchase = $aMinuteBeforePurchase->subMinute(1);
                    $aMinuteBeforePurchase->toDateTimeString();

                    $purchasePriceDetails['purchaseTimestamp'] = $purchaseTimestamp;
                    $purchasePriceDetails['aMinuteBeforePurchase'] = $aMinuteBeforePurchase;

                    $baseCurrencyRow = BaseCurrenciesRateModel::whereBetween('created_at', [$purchaseTimestamp, $aMinuteBeforePurchase])->first();

                    if (count($baseCurrencyRow) > 0) {
                        $purchasePriceDetails['BTCRateInUSDTAtTheTimeOfPurchase'] = $baseCurrencyRow->Tether_BTC_Rate;
                        $purchasePriceDetails['purchasePriceInUSDT'] = $row->PricePerUnit * $baseCurrencyRow->Tether_BTC_Rate;

                    } else {
                        $purchasePriceDetails['BTCRateInUSDTAtTheTimeOfPurchase'] = 0;
                        $purchasePriceDetails['purchasePriceInUSDT'] = 0;
                    }
                    break;
                }
            }
        }
        return $purchasePriceDetails;
    }


    public function getMarketSummary($marketSummaries, $marketName)
    {
        $marketSummary = [];
        foreach ($marketSummaries as $row) {
            if (strcasecmp($row->MarketName, $marketName) == 0) {
                $marketSummary = $row;
                break;
            }
        }
        return $marketSummary;
    }


    public function getAltcoinOrderHistory($marketName)
    {

        $bittrexMarketUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);
        $currenciesUtilities = new CurrenciesUtilities($this->apiKey, $this->secretKey);
        $USDT_To_BTC = $currenciesUtilities->getTetherBTCRateFromMarket();
        $USDT_To_BTC_24HoursBackPrice = $this->get24HoursBackUSDTPrice();

        $data = [];
        $accountError = null;
        $openOrders = [];
        $orderHistory = [];
        $percentChange = null;
        $last24HoursPercentChange = 0;
        try {

            $openOrdersRawData = $bittrexMarketUtilities->getOpenOrders($marketName);
            $openOrdersRawData = array_reverse($openOrdersRawData);

            $bittrexAccountUtilities = new BittrexAccountUtilities($this->apiKey, $this->secretKey);
            $completedOrders = $bittrexAccountUtilities->getorderhistory($marketName);

            $ticker = $marketSummary = $bittrexMarketUtilities->getMarketSummaryFromDB($marketName);

            $purchasePriceDetails = $this->getPurchasePriceDetails($completedOrders, $marketName);

            foreach ($openOrdersRawData as $openOrder) {

                $openOrder->currentBTCRateInUSDT = $USDT_To_BTC;


                if (count($purchasePriceDetails) > 0) {

                    $openOrder->BTCRateInUSDTAtTheTimeOfPurchase = $purchasePriceDetails['BTCRateInUSDTAtTheTimeOfPurchase'];

                    if ($openOrder->BTCRateInUSDTAtTheTimeOfPurchase > 0) {
                        $openOrder->percentChangeUSDTToBTC = ($USDT_To_BTC - $openOrder->BTCRateInUSDTAtTheTimeOfPurchase) / $openOrder->BTCRateInUSDTAtTheTimeOfPurchase * 100;
                    } else {
                        $openOrder->percentChangeUSDTToBTC = 0;
                    }

                    $openOrder->investedAmountInUSDT = $purchasePriceDetails['purchasePriceInUSDT'] * $openOrder->QuantityRemaining;
                    $openOrder->currentValueInUSDT = $ticker->Last * $USDT_To_BTC * $openOrder->QuantityRemaining;

                    $openOrder->purchasePricePerUnitInBTC = $purchasePriceDetails['purchasePriceInBTC'];
                    $openOrder->currentPricePerUnitInBTC = $ticker->Last;
                    $openOrder->investedAmountInBTC = $purchasePriceDetails['purchasePriceInBTC'] * $openOrder->QuantityRemaining;
                    $openOrder->currentValueInBTC = $ticker->Last * $openOrder->QuantityRemaining;

                    if ($purchasePriceDetails['purchasePriceInUSDT'] > 0) {
                        $percentChangeInUSDT = ($ticker->Last * $USDT_To_BTC - $purchasePriceDetails['purchasePriceInUSDT']) / $purchasePriceDetails['purchasePriceInUSDT'] * 100;
                        $openOrder->percentChangeInUSDT = $percentChangeInUSDT;
                    } else {
                        $openOrder->percentChangeInUSDT = 0;
                    }

                    $percentChangeInBTC = ($ticker->Last - $purchasePriceDetails['purchasePriceInBTC']) / $purchasePriceDetails['purchasePriceInBTC'] * 100;
                    $openOrder->percentChangeInBTC = $percentChangeInBTC;

                } else {
                    $openOrder->purchasePricePerUnitInBTC = 0.00;
                    $openOrder->currentPricePerUnitInBTC = 0.00;

                    $openOrder->BTCRateInUSDTAtTheTimeOfPurchase = 0.00;
                    $openOrder->percentChangeUSDTToBTC = 0.00;
                    $openOrder->investedAmountInUSDT = 0.00;
                    $openOrder->currentValueInUSDT = 0.00;

                    $openOrder->investedAmountInUSDT = 0.00;
                    $openOrder->currentValueInUSDT = 0.00;

                    $openOrder->investedAmountInBTC = 0.00;
                    $openOrder->currentValueInBTC = 0.00;

                    $openOrder->percentChangeInUSDT = 0.00;
                    $openOrder->percentChangeInBTC = 0.00;
                }

                if ($marketSummary->PrevDay != 0 && $USDT_To_BTC_24HoursBackPrice != 0) {
                    $last24HoursPercentChangeInUSDT = ($ticker->Last * $USDT_To_BTC - $marketSummary->PrevDay * $USDT_To_BTC_24HoursBackPrice) / ($marketSummary->PrevDay * $USDT_To_BTC_24HoursBackPrice) * 100;
                    $openOrder->last24HoursPercentChangeInUSDT = $last24HoursPercentChangeInUSDT;

                    $last24HoursPercentChangeInBTC = ($ticker->Last - $marketSummary->PrevDay) / $marketSummary->PrevDay * 100;
                    $openOrder->last24HoursPercentChangeInBTC = $last24HoursPercentChangeInBTC;
                } else {
                    $last24HoursPercentChangeInUSDT = 0.00;
                    $openOrder->last24HoursPercentChangeInUSDT = $last24HoursPercentChangeInUSDT;

                    $last24HoursPercentChangeInBTC = 0.00;
                    $openOrder->last24HoursPercentChangeInBTC = $last24HoursPercentChangeInBTC;
                }

                $openOrders[] = $openOrder;
            }

            foreach ($completedOrders as $row) {
                if (strcasecmp($row->OrderType, 'LIMIT_BUY') == 0) {
                    $row->altcoinToBTC = $row->Price + $row->Commission;
                    $row->altcoinToUSD = $row->altcoinToBTC * $USDT_To_BTC;
                    $orderHistory [] = $row;
                } else {
                    $row->altcoinToBTC = $row->Price - $row->Commission;
                    $row->altcoinToUSD = $row->altcoinToBTC * $USDT_To_BTC;
                    $orderHistory [] = $row;
                }
            }

            $data['orderHistory'] = $orderHistory;
            $data['openOrders'] = $openOrders;
            $data['ticker'] = $ticker;
            $data['percentChange'] = $percentChange;
            $data['last24HoursPercentChange'] = $last24HoursPercentChange;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' altcoinOrderHistory exception: ' . 'inputs' . serialize(Input::All()) . '   exception ' . $exception);
        }
        return $data;
    }


}