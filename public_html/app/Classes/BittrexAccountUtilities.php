<?php

namespace App\Classes;

use Log;

class BittrexAccountUtilities
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


    public function getOrderHistory($market = null, $count = null)
    {
        $orderHistory = [];

        try {
            $orderHistory = $this->bittrexAPIs->getorderhistory($market, $count);

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' $exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
            $accountError = 'An exception has occurred, please contact support team for help: ' . $exception->getMessage();
            $orderHistoryData['accountError'] = $accountError;

        }
        return $orderHistory;
    }


    public function getAltcoinsBalance()
    {
        $altcoinsBalance = [];

        try {

            $currenciesIncludingZeroBalance = $this->bittrexAPIs->getBalances();

            $currenciesWithBalance = null;
            foreach ($currenciesIncludingZeroBalance as $row) {
                if ($row->Balance > 0) {
                    $currenciesWithBalance[] = $row;
                }
            }
            foreach ($currenciesWithBalance as $row) {
                $currencyName = $row->Currency;
                if (strcasecmp($currencyName, 'BTC') != 0 && strcasecmp($currencyName, 'USDT') != 0) {
                    $altcoinsBalance[] = $row;
                }
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' $exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
        }
        return $altcoinsBalance;
    }


    public function getCryptoCurrenciesBalance()
    {
        $altcoinsBalanceData = [];

        $tickers = null;
        $altcoinsBalance = null;
        $totalBalanceIn_BTC = 0;
        $totalBalanceIn_USDT = 0;
        $marketName = null;
        $quantity = null;
        $rate = null;
        $accountError = null;


        try {

            $ticker = $this->bittrexAPIs->getTicker('USDT-BTC');
            $USDT_To_BTC = $ticker->Last;

            $currenciesIncludingZeroBalance = $this->bittrexAPIs->getBalances();

            $currenciesWithBalance = [];
            foreach ($currenciesIncludingZeroBalance as $row) {
                if ($row->Balance > 0) {
                    $currenciesWithBalance[] = $row;
                }
            }

            foreach ($currenciesWithBalance as $row) {

                $currencyName = $row->Currency;

                if (strcasecmp($currencyName, 'BTC') == 0) {
                    $quantity = $row->Balance;
                    $row->altcoinToUSDT = $quantity * $USDT_To_BTC;
                    $row->altcoinToBTC = $quantity;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                    $altcoinsBalance[] = $row;
                } elseif (strcasecmp($currencyName, 'USDT') == 0) {
                    $quantity = $row->Balance;
                    $row->altcoinToBTC = $quantity / $USDT_To_BTC;
                    $row->altcoinToUSDT = $quantity;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                    $altcoinsBalance[] = $row;
                } else {
                    $marketName = $this->BTCPrefix . $row->Currency;
                    $bittrexUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);
                    $ticker = $bittrexUtilities->getTickerDataFromDB($marketName);

                    $rate = $ticker->Last;
                    $quantity = $row->Balance;
                    $row->altcoinToBTC = $rate * $quantity;
                    $row->altcoinToUSDT = $rate * $quantity * $USDT_To_BTC;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                    $altcoinsBalance[] = $row;
                }

            }
            $accountBalance = $this->getUSDTAndBTCBalance();

            $altcoinsBalanceData['altcoinsBalance'] = $altcoinsBalance;
            $altcoinsBalanceData['totalBalanceIn_BTC'] = $totalBalanceIn_BTC;
            $altcoinsBalanceData['totalBalanceIn_USDT'] = $totalBalanceIn_USDT;
            $altcoinsBalanceData['accountBalance'] = $accountBalance;
            $altcoinsBalanceData['accountError'] = null;


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' $exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
            $accountError = 'An exception has occurred, please contact support team for help: ' . $exception->getMessage();
            $altcoinsBalanceData['accountError'] = $accountError;

        }
        return $altcoinsBalanceData;
    }


    public function getUSDTAndBTCBalance()
    {

        $tickers = null;
        $totalBalanceIn_BTC = 0;
        $totalBalanceIn_USDT = 0;
        $marketName = null;
        $quantity = null;
        $rate = null;
        $accountError = null;

        try {

            $ticker = $this->bittrexAPIs->getTicker('USDT-BTC');
            $USDT_To_BTC = $ticker->Last;

            $currenciesIncludingZeroBalance = $this->bittrexAPIs->getBalances();

            $currenciesWithBalance = [];
            foreach ($currenciesIncludingZeroBalance as $row) {
                if ($row->Balance > 0) {
                    $currenciesWithBalance[] = $row;
                }
            }
            foreach ($currenciesWithBalance as $row) {

                $currencyName = $row->Currency;
                if (strcasecmp($currencyName, 'BTC') == 0) {
                    $quantity = $row->Balance;
                    $row->altcoinToUSDT = $quantity * $USDT_To_BTC;
                    $row->altcoinToBTC = $quantity;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                } elseif (strcasecmp($currencyName, 'USDT') == 0) {
                    $quantity = $row->Balance;
                    $row->altcoinToBTC = $quantity / $USDT_To_BTC;
                    $row->altcoinToUSDT = $quantity;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                } else {
                    $marketName = $this->BTCPrefix . $row->Currency;
                    $bittrexUtilities = new BittrexMarketUtilities($this->apiKey, $this->secretKey);
                    $ticker = $bittrexUtilities->getTickerDataFromDB($marketName);
                    $rate = $ticker->Last;
                    $quantity = $row->Balance;
                    $row->altcoinToBTC = $rate * $quantity;
                    $row->altcoinToUSDT = $rate * $quantity * $USDT_To_BTC;
                    $totalBalanceIn_BTC = $totalBalanceIn_BTC + $row->altcoinToBTC;
                    $totalBalanceIn_USDT = $totalBalanceIn_USDT + $row->altcoinToUSDT;
                }

            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
        }

        $accountBalance = [];
        $accountBalance['totalBalanceIn_BTC'] = $totalBalanceIn_BTC;
        $accountBalance['totalBalanceIn_USDT'] = $totalBalanceIn_USDT;
        return $accountBalance;

    }


    public function getBalance($marketName)
    {
        $balance = 0;

        try {
            $balance = $this->bittrexAPIs->getBalance($marketName);

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'inputs' . serialize(Input::All()) . '   exception' . $exception);
        }
        return $balance;
    }


}