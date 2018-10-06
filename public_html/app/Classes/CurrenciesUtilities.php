<?php

namespace App\Classes;

use Log;
use App\Models\BaseCurrenciesRateModel;
use App\Classes\BittrexAPIs;

class CurrenciesUtilities
{

    public function getZebpayRateFromMarket()
    {
        try {
            $getCurrency = "inr";
            $displayArrayOutput = true;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.zebapi.com/api/v1/market/ticker/btc/inr",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $tickers = '';
            } else {
                if ($displayArrayOutput) {
                    $tickers = json_decode($response, true);
                } else {
                    header("Content-type:application/json");
                    $tickers = $response;
                }
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            return '';
        }
        return $tickers;
    }


    public function getUSDINRRateFromMarket()
    {
        $USDINRRate = null;
        try {
            $url = "http://api.fixer.io/latest?base=USD";
            $allCurrenciesRate = file_get_contents($url);
            $allCurrenciesRate = json_decode($allCurrenciesRate, true);
            $USDINRRate = $allCurrenciesRate['rates']['INR'];
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
        return $USDINRRate;
    }


    public function getLocalBitCoinsRateYourOwnBuyAddFromMarket($countryCode, $countryName, $paymentMethod)
    {

        $localBitcoinsData = null;
        try {
            $url = "https://localbitcoins.com/buy-bitcoins-online/" . $countryCode . "/" . $countryName . "/" . $paymentMethod . "/.json";
            $result = file_get_contents($url);
            $result = json_decode($result, true);
            $data = $result['data'];
            $adList = $data['ad_list'];
            $firstAd = $adList[0]['data'];

            $localBitcoinsData['temp_price'] = $firstAd['temp_price'];
            $localBitcoinsData['temp_price_usd'] = $firstAd['temp_price_usd'];
            $localBitcoinsData['min_amount'] = $firstAd['min_amount'];
            $localBitcoinsData['max_amount'] = $firstAd['max_amount'];
            $localBitcoinsData['location_string'] = $firstAd['location_string'];
            $localBitcoinsData['currency'] = $firstAd['currency'];

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
        return $localBitcoinsData;
    }


    public function getLocalBitCoinsRateYourOwnSellAddFromMarket($countryCode, $countryName, $paymentMethod)
    {

        $localBitcoinsData = null;
        try {
            $url = "https://localbitcoins.com/sell-bitcoins-online/" . $countryCode . "/" . $countryName . "/" . $paymentMethod . "/.json";
            $result = file_get_contents($url);
            $result = json_decode($result, true);
            $data = $result['data'];
            $adList = $data['ad_list'];
            $firstAd = $adList[0]['data'];

            $localBitcoinsData['temp_price'] = $firstAd['temp_price'];
            $localBitcoinsData['temp_price_usd'] = $firstAd['temp_price_usd'];
            $localBitcoinsData['min_amount'] = $firstAd['min_amount'];
            $localBitcoinsData['max_amount'] = $firstAd['max_amount'];
            $localBitcoinsData['location_string'] = $firstAd['location_string'];
            $localBitcoinsData['currency'] = $firstAd['currency'];

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
        return $localBitcoinsData;
    }


    public function getUSDBTCRateFromMarket()
    {
        try {
            $url = "https://blockchain.info/ticker";
            $tickers = file_get_contents($url);
            $tickers = json_decode($tickers, true);

            $BTCToUSD = $tickers['USD']['last'];
            return $BTCToUSD;
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
    }

    public function getTetherBTCRateFromMarket()
    {
        try {
            $bittrexAPIs = new BittrexAPIs(null, null);
            $ticker = $bittrexAPIs->getTicker('USDT-BTC');
            return $ticker->Last;
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
    }


    public function getUSDBTCRateFromDB()
    {
        $baseCurrencyRow = BaseCurrenciesRateModel::orderBy('created_at', 'desc')->first();
        $blockchainUSDRate = $baseCurrencyRow->USD_BTC_Rate;
        return $blockchainUSDRate;
    }

    public function getTetherBTCRateFromDB()
    {
        $baseCurrencyRow = BaseCurrenciesRateModel::orderBy('created_at', 'desc')->first();
        $tetherBTCRate = $baseCurrencyRow->Tether_BTC_Rate;
        return $tetherBTCRate;
    }


    public function getArbitrageOpportunity()
    {
        $data = [];

        try {

            $zepPayRateFromMarket = $this->getZebpayRateFromMarket();
            $zeppayINRRate = $zepPayRateFromMarket['sell'];

            $USDINRRate = $this->getUSDINRRateFromMarket();
            $blockchainUSDRate = $this->getUSDBTCRateFromDB();

            $countryCode = 'AE';
            $countryName = 'united-arab-emirates';
            $paymentMethod = 'national-bank-transfer';
//            $countryCode = 'IN';
//            $countryName = 'india';
//            $paymentMethod = 'imps-bank-transfer-india';
            $localBitcoinsData = $this->getLocalBitCoinsRateYourOwnBuyAddFromMarket($countryCode, $countryName, $paymentMethod);
            if ($zeppayINRRate != null && $USDINRRate != null && $blockchainUSDRate != null && $localBitcoinsData != null) {

                $blockchainINRRate = $blockchainUSDRate * $USDINRRate;
                $percentageDifferenceBlockchainZebpay = ($zeppayINRRate - $blockchainINRRate) / $blockchainINRRate * 100;
                $profitBlockchainZebpay = $zeppayINRRate - $blockchainINRRate;

                $LocalBitcoinsINRRate = $localBitcoinsData['temp_price_usd'] * $USDINRRate;
                $percentageDifferenceLocalBitcoinsZebpay = ($zeppayINRRate - $LocalBitcoinsINRRate) / $LocalBitcoinsINRRate * 100;
                $profitLocalBitcoinsZebpay = $zeppayINRRate - $LocalBitcoinsINRRate;

                $data['temp_price_inr'] = $LocalBitcoinsINRRate;
                $data['percentageDifferenceLocalBitcoinsZebpay'] = $percentageDifferenceLocalBitcoinsZebpay;
                $data['profitLocalBitcoinsZebpay'] = $profitLocalBitcoinsZebpay;

                $data['USDINRRate'] = $USDINRRate;
                $data['zeppayINRRate'] = $zeppayINRRate;
                $data['blockchainUSDRate'] = $blockchainUSDRate;
                $data['blockchainINRRate'] = $blockchainINRRate;
                $data['percentageDifferenceBlockchainZebpay'] = $percentageDifferenceBlockchainZebpay;
                $data['profitBlockchainZebpay'] = $profitBlockchainZebpay;
                $data['temp_price'] = $localBitcoinsData['temp_price'];
                $data['temp_price_usd'] = $localBitcoinsData['temp_price_usd'];
                $data['min_amount'] = $localBitcoinsData['min_amount'];
                $data['max_amount'] = $localBitcoinsData['max_amount'];
                $data['location_string'] = $localBitcoinsData['location_string'];
                $data['currency'] = $localBitcoinsData['currency'];

            }


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $data;
    }


    public function getReverseArbitrageOpportunity()
    {
        $data = [];

        try {

            $zepPayRateFromMarket = $this->getZebpayRateFromMarket();
            $zeppayINRRate = $zepPayRateFromMarket['buy'];

            $USDINRRate = $this->getUSDINRRateFromMarket();
            $blockchainUSDRate = $this->getUSDBTCRateFromDB();

            $countryCode = 'AE';
            $countryName = 'united-arab-emirates';
            $paymentMethod = 'national-bank-transfer';
            $localBitcoinsData = $this->getLocalBitCoinsRateYourOwnSellAddFromMarket($countryCode, $countryName, $paymentMethod);

            if ($zeppayINRRate != null && $USDINRRate != null && $blockchainUSDRate != null && $localBitcoinsData != null) {

                $blockchainINRRate = $blockchainUSDRate * $USDINRRate;
                $percentageDifferenceBlockchainZebpay = ($blockchainINRRate - $zeppayINRRate) / $blockchainINRRate * 100;
                $profitBlockchainZebpay = $blockchainINRRate - $zeppayINRRate;

                $LocalBitcoinsINRRate = $localBitcoinsData['temp_price_usd'] * $USDINRRate;
                $percentageDifferenceLocalBitcoinsZebpay = ($LocalBitcoinsINRRate - $zeppayINRRate) / $LocalBitcoinsINRRate * 100;
                $profitLocalBitcoinsZebpay = $LocalBitcoinsINRRate - $zeppayINRRate;

                $data['temp_price_inr'] = $LocalBitcoinsINRRate;
                $data['percentageDifferenceLocalBitcoinsZebpay'] = $percentageDifferenceLocalBitcoinsZebpay;
                $data['profitLocalBitcoinsZebpay'] = $profitLocalBitcoinsZebpay;

                $data['USDINRRate'] = $USDINRRate;
                $data['zeppayINRRate'] = $zeppayINRRate;
                $data['blockchainUSDRate'] = $blockchainUSDRate;
                $data['blockchainINRRate'] = $blockchainINRRate;
                $data['percentageDifferenceBlockchainZebpay'] = $percentageDifferenceBlockchainZebpay;
                $data['profitBlockchainZebpay'] = $profitBlockchainZebpay;
                $data['temp_price'] = $localBitcoinsData['temp_price'];
                $data['temp_price_usd'] = $localBitcoinsData['temp_price_usd'];
                $data['min_amount'] = $localBitcoinsData['min_amount'];
                $data['max_amount'] = $localBitcoinsData['max_amount'];
                $data['location_string'] = $localBitcoinsData['location_string'];
                $data['currency'] = $localBitcoinsData['currency'];

            }


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $data;
    }

}