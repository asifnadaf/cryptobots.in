<?php

namespace App\Http\Controllers\BittrexExchange;


use App\Classes\BittrexUSDTMarketUtilities;
use App\Models\KoinexBittrexArbitrageOpportunitiesTrackerModel;
use Illuminate\Support\Facades\Auth;
use LaravelAcl\Authentication\Models\UserProfile;

use App\Classes\BittrexBots\Support\BitcoinReverseArbitrageOpportunitiesBot;
use App\Classes\BittrexBots\Support\RecordBittrexBTCIndexBot;
use App\Classes\BittrexBots\Support\SellOnResistancePriceBot;
use App\Classes\BittrexBots\Support\SendEmailWhenBotsNotWorkingBot;
use App\Models\BotSettingsModel;
use App\Models\BotRunningStatusModel;
use LaravelAcl\Authentication\Tests\Unit\UserControllerTest;
use Log;
use Mail;
use Carbon\Carbon;
use App\Models\BittrexBTCIndexModel;
use App\Models\BaseCurrenciesRateModel;
use App\Models\BitcoinArbitrageOpportunitiesModel;

use App\Models\DailyMarketDataModel;
use App\Classes\BittrexMarketUtilities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

use App\Classes\BittrexGeneralUtilities;
use App\Models\BalanceHistoryModel;
use App\Models\ClientsListModel;
use App\Models\PastMarketStatisticsModel;

use App\Classes\BittrexKoinexArbitrageBots\RecordKoinexTickersBot;
use App\Classes\BittrexBots\Support\BuyAtSupportBot;
use App\Classes\BittrexBots\Support\RecordBalanceHistoryBot;
use App\Classes\BittrexBots\Support\RecordDailyMarketSummaryBot;
use App\Classes\BittrexBots\Support\RecordEveryMinuteSupportResistanceBot;
use App\Classes\BittrexBots\Support\RecordEveryMinuteMarketOddsBot;
use App\Classes\BittrexBots\Support\ReportAboveResistancePriceBot;
use App\Classes\BittrexBots\Support\RecordMarketDelistingBot;
use App\Classes\BittrexBots\Support\RecordMarketListingBot;
use App\Classes\BittrexBots\Support\ReportPumpAndDumpBot;
use App\Classes\BittrexBots\Support\ReportXPercentBelowSupportPriceBot;
use App\Classes\BittrexBots\Support\UpdateSellLimitOrderBookToXTimesBot;
use App\Classes\BittrexBots\Support\BitcoinArbitrageOpportunitiesBot;

use Psy\Test\Exception\RuntimeExceptionTest;
use Charts;
use App\Http\Requests;
use LaravelAcl\Authentication\Interfaces\AuthenticateInterface;
use LaravelAcl\Authentication\Controllers\UserController;
use App\Models\MarketPumpsModel;
use LaravelAcl\Authentication\Classes\SentryAuthenticator;
use LaravelAcl\Authentication\Helpers\SentryAuthenticationHelper;
use App\Classes\BittrexAPIs;
use App\Classes\BittrexKoinexArbitrageUtilities\BittrexKoinexArbitrageUtilities;
use DOMDocument;
use DOMXPath;

use App\Classes\CloudflareBypass\cloudflareClass;
use App\Classes\CloudflareBypass\httpProxyClass;
use App\Models\BittrexKoinexArbitrageSettingsModel;
use App\Classes\BittrexKoinexArbitrageUtilities\ArbitrageOverviewUtilities;
use App\Classes\BittrexKoinexArbitrageBots\KoinexBittrexArbitrageOpportunitiesTrackerBot;
use App\Classes\IndianExchangesArbitrageBot\IndianExchangesArbitrageBot;

class TestController extends Controller
{

    public function test()
    {


        $object = new BitcoinArbitrageOpportunitiesBot();
        $result = $object->bitcoinArbitrageOpportunitiesBot();
        return $result;



        return;

        $obj = new RecordBalanceHistoryBot();
        return $obj->setBalanceHistory();

        return 'test';


        $marketName = 'BTC-WAVES';
        $quantity = 1;
        $rate = '0.00075613';

        $settings = BittrexKoinexArbitrageSettingsModel::first();
        $apiKey = $settings->apiKey;
        $secretKey = $settings->secretKey;

        $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);

        try {

            $sellLimitResponse['response'] = $bittrexAPIs->buyLimit($marketName, $quantity, $rate);
            return $sellLimitResponse;
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex details ' . serialize($sellLimitResponse['status']));

        } catch (Exception $exception) {
            $sellLimitResponse['status'] = $exception->getMessage();
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex exception: ' . $exception);
        }




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


        return $koinexXRPTickerData;
//        $marketName = 'BTC-BCC';
//        $quantity = 0.00179236333;
//        $rate = 0.17215099;
//
//        $settings = BittrexKoinexArbitrageSettingsModel::first();
//        $apiKey = $settings->apiKey;
//        $secretKey = $settings->secretKey;
//        $bittrexAPIs = new BittrexAPIs($apiKey, $secretKey);
//
//        try {
//
//            $sellLimitResponse['status'] = $bittrexAPIs->buyLimit($marketName, $quantity, $rate);
//            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex details ' . serialize($sellLimitResponse['status']));
//            return $sellLimitResponse['status'];
//
//
//        } catch (Exception $exception) {
//            $sellLimitResponse['status'] = $exception->getMessage();
//            Log::info(get_class($this) . '->' . __FUNCTION__ . ' buyOnBittrex exception: ' . $exception);
//            return $sellLimitResponse['status'];
//
//        }


        $currencyName = 'bitcoin';
        $requestLink = 'https://koinex.in/exchange/' . 'bitcoin';
        return $this->scrapeKoinexTickerData($requestLink);

        return 'from here';


    }
    public function removeNegativeReturns()
    {
        $allData = KoinexBittrexArbitrageOpportunitiesTrackerModel::all();

        foreach ($allData as $row){
            if($row->grossPercentGain < 1.5){
                KoinexBittrexArbitrageOpportunitiesTrackerModel::where('id',$row->id)->delete();
            }

        }

    }
    public function scrapeKoinexTickerData($requestLink)
    {

        ini_set('display_errors', 1);

        $httpProxy = new httpProxyClass();
        $httpProxyUA = 'proxyFactory';

        $requestPage = json_decode($httpProxy->performRequest($requestLink));

// if page is protected by cloudflare
        if ($requestPage->status->http_code == 503) {
            // Make this the same user agent you use for other cURL requests in your app
            cloudflareClass::useUserAgent($httpProxyUA);

            // attempt to get clearance cookie
            if ($clearanceCookie = cloudflareClass::bypass($requestLink)) {
                // use clearance cookie to bypass page
                $requestPage = $httpProxy->performRequest($requestLink, 'GET', null, array(
                    'cookies' => $clearanceCookie
                ));
                // return real page content for site
                $requestPage = json_decode($requestPage);
                echo $requestPage->content;
            } else {
                // could not fetch clearance cookie
                echo 'Could not fetch CloudFlare clearance cookie (most likely due to excessive requests)';
            }
        }


    }


    public function getBittrexMarketSummary($marketName)
    {
        $bittrexAPIs = new BittrexAPIs(null, null);
        $response = $bittrexAPIs->getMarketSummary($marketName);;
        return $response;

    }

    public function getKoinexMarket()
    {
        try {

            $url = "https://koinex.in/api/ticker";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $html = curl_exec($ch);
            curl_close($ch);

            $html = json_decode($html, true);

            return $html;

            $url = "https://koinex.in/api/ticker";
            $response = file_get_contents($url);
            $response = json_decode($response, true);
            return $response;


            $getCurrency = "inr";
            $displayArrayOutput = true;

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://koinex.in/api/ticker",
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

            return $response;

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

    public function correctTimeError()
    {

        $altcoinHistoricalData = DailyMarketDataModel::orderBy('created_at', 'asc')->get();

        foreach ($altcoinHistoricalData as $row) {
            $date = Carbon::createFromFormat('Y-m-d G:i:s', $row->created_at);
            $date->setTime(00, 01, 15);

            $dbTableRow = DailyMarketDataModel::find($row->id);
            $dbTableRow->created_at = $date;
            $dbTableRow->save();
        }
        return 'success';
    }

    public function getStats()
    {

        $start = Carbon::parse('2017-07-04 00:00:00', 'UCT')->format('Y-m-d G:i:s');
        $start = Carbon::createFromFormat('Y-m-d G:i:s', $start);

        $end = Carbon::parse('2017-07-04 23:59:59', 'UCT')->format('Y-m-d G:i:s');
        $end = Carbon::createFromFormat('Y-m-d G:i:s', $end);

        $BTCPrefix = 'BTC-';
        $readabilityFactor = 10000;
        $count = 103;
        do {
            $altcoinHistoricalData = DailyMarketDataModel::whereBetween('created_at', [$start, $end])->orderBy('created_at', 'asc')->get();

            $sumOf24HoursBackPriceBittrexIndex = 0;
            $sumOfCurrentPriceBittrexIndex = 0;

            $twentyFourHoursBackPriceBTC = 0;
            $CurrentPriceBTC = 0;
            $indexSize = 0;
            foreach ($altcoinHistoricalData as $row) {
                if (strcasecmp($row->marketName, 'USDT-BTC') == 0) {
                    $twentyFourHoursBackPriceBTC = $row->PrevDay;
                    $CurrentPriceBTC = $row->Last;

                } elseif (strpos($row->marketName, $BTCPrefix) !== false) {
                    $sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex + $row->PrevDay;
                    $sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex + $row->Last;
                    $indexSize++;
                }
            }

            $sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex * $readabilityFactor;
            $sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex * $readabilityFactor;

            $bittrexBTCIndexModel = new BittrexBTCIndexModel();

            $flag1 = false;
            if ($sumOf24HoursBackPriceBittrexIndex > 0) {
                $bittrexBTCIndexModel->sumOf24HoursBackPriceBittrexIndex = $sumOf24HoursBackPriceBittrexIndex;
                $bittrexBTCIndexModel->sumOfCurrentPriceBittrexIndex = $sumOfCurrentPriceBittrexIndex;
                $bittrexBTCIndexModel->percentageDifferenceBittrexIndex = ($sumOfCurrentPriceBittrexIndex - $sumOf24HoursBackPriceBittrexIndex) / $sumOf24HoursBackPriceBittrexIndex * 100;
                $flag1 = true;
            }

            $flag2 = false;
            if ($twentyFourHoursBackPriceBTC > 0) {
                $bittrexBTCIndexModel->twentyFourHoursBackPriceBTC = $twentyFourHoursBackPriceBTC;
                $bittrexBTCIndexModel->CurrentPriceBTC = $CurrentPriceBTC;
                $bittrexBTCIndexModel->percentageDifferenceBTC = ($CurrentPriceBTC - $twentyFourHoursBackPriceBTC) / $twentyFourHoursBackPriceBTC * 100;
                $flag2 = true;
            }

            $flag3 = false;
            if ($sumOf24HoursBackPriceBittrexIndex > 0 && $twentyFourHoursBackPriceBTC > 0) {
                $bittrexBTCIndexModel->twentyFourHoursBackProduct = $sumOf24HoursBackPriceBittrexIndex * $twentyFourHoursBackPriceBTC / $readabilityFactor;
                $bittrexBTCIndexModel->CurrentPriceProduct = $sumOfCurrentPriceBittrexIndex * $CurrentPriceBTC / $readabilityFactor;
                $bittrexBTCIndexModel->percentageDifferenceProduct = ($bittrexBTCIndexModel->CurrentPriceProduct - $bittrexBTCIndexModel->twentyFourHoursBackProduct) / $bittrexBTCIndexModel->twentyFourHoursBackProduct * 100;
                $flag3 = true;
            }


            if ($flag1 || $flag2 || $flag3) {
                $bittrexBTCIndexModel->indexSize = $indexSize;
                $bittrexBTCIndexModel->created_at = $start;
                $bittrexBTCIndexModel->updated_at = $start;
                $bittrexBTCIndexModel->save();

            }


            $count--;
            $start->addHours(24);
            $end->addHours(24);
        } while ($count > 0);

    }


}
