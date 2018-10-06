<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\BittrexBTCIndexModel;
use App\Models\MarketOddsModel;
use Log;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Classes\BittrexMarketUtilities;
use App\Models\PastMarketStatisticsModel;

class PastMarketStatisticsController extends Controller
{

    public function index()
    {

        $response = $this->getPastPumpsAndDumps();
        $data = $response['data'];

        usort($data, function ($a, $b) {
            return $a['percentChange'] <=> $b['percentChange'];
        });

        $summary = $response['summary'];
        return View::make('bittrex/pastmarketstatistics/index', compact('data','summary'));
    }


    public function getPastPumpsAndDumps()
    {
        $bittrexMarketUtilities = new BittrexMarketUtilities(null, null);
        $intermediate = $bittrexMarketUtilities->getMarketSummariesWithDailyPercentageReturns();

        $marketSummaries = [];
        foreach ($intermediate as $row) {
            if (strcasecmp($row->MarketName, 'USDT-BTC') !== 0) {
                $marketSummaries[] = $row;
            }
        }

        $temp = null;
        $result = [];

        $daysCoinsCount = 0;
        $allNumberOfPumps200 = 0;
        $allNumberOfPumps100 = 0;
        $allNumberOfPumps50 = 0;
        $allNumberOfPumps30 = 0;
        $allNumberOfDumps50 = 0;
        $allNumberOfDumps33 = 0;

        $allListedPrice = 0;
        $allCurrentPrice = 0;
        $marketsCount = 0;

        foreach ($marketSummaries as $item) {
            $marketsCount++;
            $marketName = $item->MarketName;
            $data = PastMarketStatisticsModel::where('MarketName', '=', $marketName)->get();

            $numberOfDays = 0;
            $previousDayData = null;
            $listedDayData = null;
            $numberOfPumps200 = 0;
            $numberOfPumps100 = 0;
            $numberOfPumps50 = 0;
            $numberOfPumps30 = 0;
            $numberOfDumps50 = 0;
            $numberOfDumps33 = 0;
            foreach ($data as $row) {


                if ($numberOfDays <= 30) {
                    $listedDayData = $row;
                    $previousDayData = $row;
                    $numberOfDays++;
                    continue;
                }
                $todaysData = $row;
                $purchasePrice = $previousDayData->C;
                $sellingPriceHigh = $todaysData->H;
                $sellingPriceLow = $todaysData->L;

                $percentChangeHighest = ($sellingPriceHigh - $purchasePrice) / $purchasePrice * 100;
                $percentChangeLowest = ($sellingPriceLow - $purchasePrice) / $purchasePrice * 100;

                if ($percentChangeHighest > 200) {
                    $numberOfPumps200++;
                }

                if ($percentChangeHighest > 100) {
                    $numberOfPumps100++;
                }

                if ($percentChangeHighest > 50) {
                    $numberOfPumps50++;
                }

                if ($percentChangeHighest > 30) {
                    $numberOfPumps30++;
                }

                if ($percentChangeLowest < -50) {
                    $numberOfDumps50++;
                }

                if ($percentChangeLowest < -33.33) {
                    $numberOfDumps33++;
                }

                $previousDayData = $row;
                $numberOfDays++;
            }
            $summary['marketName'] = $marketName;
            $summary['numberOfPumps200'] = $numberOfPumps200;
            $summary['numberOfPumps100'] = $numberOfPumps100;
            $summary['numberOfPumps50'] = $numberOfPumps50;
            $summary['numberOfPumps30'] = $numberOfPumps30;

            $summary['numberOfDumps50'] = $numberOfDumps50;
            $summary['numberOfDumps33'] = $numberOfDumps33;
            $summary['numberOfDays'] = $numberOfDays;

            $purchasePrice = $listedDayData->C;
            $sellingPrice = $previousDayData->C;
            $percentChange = ($sellingPrice - $purchasePrice) / $purchasePrice * 100;
            $summary['listedPrice'] = $purchasePrice;
            $summary['currentPrice'] = $sellingPrice;
            $summary['percentChange'] = $percentChange;


            $daysCoinsCount = $daysCoinsCount + $numberOfDays;
            $allNumberOfPumps200 = $allNumberOfPumps200 + $numberOfPumps200;
            $allNumberOfPumps100 = $allNumberOfPumps100 + $numberOfPumps100;
            $allNumberOfPumps50 = $allNumberOfPumps50 + $numberOfPumps50;
            $allNumberOfPumps30 = $allNumberOfPumps30 + $numberOfPumps30;
            $allNumberOfDumps50 = $allNumberOfDumps50 + $numberOfDumps50;
            $allNumberOfDumps33 = $allNumberOfDumps33 + $numberOfDumps33;

            $allListedPrice = $allListedPrice + $purchasePrice;
            $allCurrentPrice = $allCurrentPrice + $sellingPrice;

            $result [] = $summary;
        }


        $summary = [];
        $summary['marketsCount'] = $marketsCount;
        $summary['daysCoinsCount'] = $daysCoinsCount;
        $summary['allNumberOfPumps200'] = $allNumberOfPumps200;
        $summary['allNumberOfPumps100'] = $allNumberOfPumps100;
        $summary['allNumberOfPumps50'] = $allNumberOfPumps50;
        $summary['allNumberOfPumps30'] = $allNumberOfPumps30;

        $summary['allNumberOfDumps50'] = $allNumberOfDumps50;
        $summary['allNumberOfDumps33'] = $allNumberOfDumps33;
        $summary['allListedPrice'] = $allListedPrice;
        $summary['allCurrentPrice'] = $allCurrentPrice;
        $summary['allPercentReturn'] = ($allCurrentPrice - $allListedPrice) / $allListedPrice * 100;;
        $result ['data'] = $result;
        $result ['summary'] = $summary;

        return $result;
    }


    public function getNewlyListedMarketsReturn()
    {
        $bittrexMarketUtilities = new BittrexMarketUtilities(null, null);
        $intermediate = $bittrexMarketUtilities->getMarketSummariesWithDailyPercentageReturns();

        $marketSummaries = [];
        foreach ($intermediate as $row) {
            if (strcasecmp($row->MarketName, 'USDT-BTC') !== 0) {
                $marketSummaries[] = $row;
            }
        }

        $temp = null;
        $result = [];
        $countNegativeReturns = 0;
        $countPositiveReturns = 0;
        $numberOfMarkets = 0;
        $sum = 0;
        $endTime = 37;

        foreach ($marketSummaries as $row) {
            $marketName = $row->MarketName;
            $data = PastMarketStatisticsModel::where('MarketName', '=', $marketName)->get();
            if (count($data) > $endTime) {
                $purchasePrice = $data[6]['C'];
                $sellingPrice = $data[$endTime - 1]['C'];
                $temp['MarketName'] = $data[6]['MarketName'];
                $temp['purchasePrice'] = $purchasePrice;
                $temp['sellingPrice'] = $sellingPrice;
                $temp['purchasePriceDate'] = $data[6]['T'];
                $temp['sellingPriceDate'] = $data[$endTime - 1]['T'];
                $percentChange = ($sellingPrice - $purchasePrice) / $purchasePrice * 100;
                $temp['percentChange'] = $percentChange;
//                $result[] = $temp;

                $numberOfMarkets++;
                $sum = $sum + (100 + $percentChange);
                if ($percentChange < 0) {
                    $countNegativeReturns++;
                } else {
                    $countPositiveReturns++;
                }
            }
        }

        $summary['numberOfDays'] = $endTime;
        $summary['numberOfMarkets'] = $numberOfMarkets;
        $summary['countNegativeReturns'] = $countNegativeReturns;
        $summary['countPositiveReturns'] = $countPositiveReturns;
        $summary['invested'] = $numberOfMarkets * 100;
        $summary['return'] = $sum;
        $summary['percentReturn'] = ($sum - $summary['invested']) / $summary['invested'] * 100;

        $result [] = $summary;

        return $result;
    }


    public function getPastDailyData()
    {
        $bittrexMarketUtilities = new BittrexMarketUtilities(null, null);
        $marketSummaries = $bittrexMarketUtilities->getMarketSummariesWithDailyPercentageReturns();
        foreach ($marketSummaries as $row) {
            $marketName = $row->MarketName;
            $result = $this->getPastDataOfMarket($marketName);
            PastMarketStatisticsModel::insert($result);
        }
    }


    public function getPastDataOfMarket($marketName)
    {
        $interval = 'day';
        $data = null;
        try {
            $url = "https://bittrex.com/Api/v2.0/pub/market/GetTicks?marketName=" . $marketName . "&tickInterval=" . $interval . "&_=1499127220008";
            $response = file_get_contents($url);
            $response = json_decode($response, true);
            $response = $response['result'];

            foreach ($response as $row) {
                $row['MarketName'] = $marketName;
                $row['created_at'] = Carbon::now();
                $row['updated_at'] = Carbon::now();
                $data[] = $row;
            }


        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
        return $data;
    }


}
