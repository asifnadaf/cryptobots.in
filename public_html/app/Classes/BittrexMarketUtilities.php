<?php

namespace App\Classes;

use Log;
use Carbon\Carbon;
use App\Models\MarketOddsModel;
use App\Models\MarketListingModel;

class BittrexMarketUtilities
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

    public function getTickerDataFromDB($marketName)
    {
        $tickerData = MarketOddsModel::where('MarketName', '=', $marketName)->first();
        return $tickerData;
    }

    public function getMarketSummaryFromDB($marketName)
    {
        $marketSummary = MarketOddsModel::where('MarketName', '=', $marketName)->first();
        return $marketSummary;
    }

    public function getMarketSummariesFromDB()
    {
        $marketSummaries = MarketOddsModel::all();
        return $marketSummaries;
    }


    public function getMarketSummaries($startTime)
    {
        $getMarketSummaries = [];
        try {
            $getMarketSummaries = $this->bittrexAPIs->getMarketSummaries();

            if (count($getMarketSummaries) <= 0) {
                $endTime = Carbon::now();
                $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' running time: ' . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $getMarketSummaries;
    }


    public function getMarketSummariesWithDailyPercentageReturns()
    {
        $updatedData = [];

        try {
            $marketSummaries = $this->bittrexAPIs->getMarketSummaries();
            if (count($marketSummaries)) {
                foreach ($marketSummaries as $marketSummary) {
                    $marketName = $marketSummary->MarketName;
                    if (strpos($marketName, $this->BTCPrefix) !== false || strcasecmp($marketName, 'USDT-BTC') == 0) {
                        $marketSummary->exchangeName = "Bittrex";

                        if ($marketSummary->PrevDay != 0) {
                            $percentChange = ($marketSummary->Last - $marketSummary->PrevDay) / $marketSummary->PrevDay * 100;
                            $marketSummary->percentChange = $percentChange;
                        } else {
                            $marketSummary->percentChange = 0;
                        }
                        $marketSummary->created_at = $marketSummary->updated_at = Carbon::now();
                        $updatedData[] = $marketSummary;
                    }
                }
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        return $updatedData;
    }


    public function getMarketSummariesWithDailyPercentageReturnsAndReturnAsArray()
    {
        $url = "https://bittrex.com/api/v1.1/public/getmarketsummaries";
        $response = file_get_contents($url);
        $json = json_decode($response, true);

        $marketSummaries = $json['result'];

        $updatedData = null;

        foreach ($marketSummaries as $marketSummary) {
            $marketSummary['exchangeName'] = "Bittrex";
            if ($marketSummary['PrevDay'] != 0) {
                $percentChange = ($marketSummary['Last'] - $marketSummary['PrevDay']) / $marketSummary['PrevDay'] * 100;
                $marketSummary['percentChange'] = $percentChange;
            } else {
                $marketSummary['percentChange'] = 0;
            }
            $marketSummary['created_at'] = $marketSummary['updated_at'] = Carbon::now();
            $updatedData[] = $marketSummary;
        }

        return $updatedData;
    }

    public function marketSummariesWithBTCAsBaseCurrency($data, $startTime)
    {
        $marketSummariesWithBTCAsBaseCurrency = [];
        try {
            foreach ($data as $row) {
                $marketName = $row->MarketName;
                if (strpos($marketName, $this->BTCPrefix) !== false) {
                    $marketSummariesWithBTCAsBaseCurrency [] = $row;
                }
            }
            if (count($marketSummariesWithBTCAsBaseCurrency) <= 0) {
                $endTime = Carbon::now();
                $differenceBetweenStartTimeAndEndTime = $startTime->diffInSeconds($endTime, false);
                Log::info(get_class($this) . '->' . __FUNCTION__ . ' start time ' . $startTime . ' end time ' . $endTime . ' $differenceBetweenStartTimeAndEndTime ' . $differenceBetweenStartTimeAndEndTime);
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $marketSummariesWithBTCAsBaseCurrency;
    }


    public function delistingMarkets()
    {
        $marketsRaw = $this->bittrexAPIs->getMarkets();
        $marketsDelistedData = [];
        $row = null;
        $the_substring = 'This market will be deleted on';
        try {
            foreach ($marketsRaw as $row) {
                if ($row->Notice != null) {
                    if (strpos($row->Notice, $the_substring) !== false) {
                        $data['MarketCurrency'] = $row->MarketCurrency;
                        $data['BaseCurrency'] = $row->BaseCurrency;
                        $data['MarketCurrencyLong'] = $row->MarketCurrencyLong;
                        $data['BaseCurrencyLong'] = $row->BaseCurrencyLong;
                        $data['MinTradeSize'] = $row->MinTradeSize;
                        $data['MarketName'] = 'BTC-UNO';
                        $data['IsActive'] = $row->IsActive;
                        $data['Created'] = $row->Created;
                        $data['Notice'] = $row->Notice;
                        $data['IsSponsored'] = $row->IsSponsored;
                        $data['LogoUrl'] = $row->LogoUrl;
                        $data['created_at'] = Carbon::now();
                        $data['updated_at'] = Carbon::now();
                        $marketsDelistedData[] = $data;
                    }
                }
            }

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' $row ' . serialize($row));
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
        return $marketsDelistedData;
    }


    public function newlyListedMarkets()
    {
        $newlyListedMarketsDataRaw = [];
        $newlyListedMarketsData = [];
        try {

            $marketsRaw = $this->bittrexAPIs->getMarkets();
            $marketsWithBTCAsBaseCurrency = [];
            foreach ($marketsRaw as $row) {
                $baseCurrency = $row->BaseCurrency;
                if (strcasecmp($baseCurrency, 'BTC') == 0) {
                    $marketsWithBTCAsBaseCurrency [] = $row;
                }
            }

            foreach ($marketsWithBTCAsBaseCurrency as $marketsWithBTCAsBaseCurrencyRow) {
                $listedDateTime = Carbon::parse($marketsWithBTCAsBaseCurrencyRow->Created, 'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A');
                $listedDateTime = Carbon::createFromFormat('d-m-Y h:i:s A', $listedDateTime);
                $currentTime = Carbon::now();
                $differenceBetweenStartTimeAndEndTime = $listedDateTime->diffInSeconds($currentTime, false);

                if ($differenceBetweenStartTimeAndEndTime < 86400) {
                    $data['MarketCurrency'] = $marketsWithBTCAsBaseCurrencyRow->MarketCurrency;
                    $data['BaseCurrency'] = $marketsWithBTCAsBaseCurrencyRow->BaseCurrency;
                    $data['MarketCurrencyLong'] = $marketsWithBTCAsBaseCurrencyRow->MarketCurrencyLong;
                    $data['BaseCurrencyLong'] = $marketsWithBTCAsBaseCurrencyRow->BaseCurrencyLong;
                    $data['MinTradeSize'] = $marketsWithBTCAsBaseCurrencyRow->MinTradeSize;
                    $data['MarketName'] = $marketsWithBTCAsBaseCurrencyRow->MarketName;
                    $data['IsActive'] = $marketsWithBTCAsBaseCurrencyRow->IsActive;
                    $data['Created'] = $marketsWithBTCAsBaseCurrencyRow->Created;
                    $data['Notice'] = $marketsWithBTCAsBaseCurrencyRow->Notice;
                    $data['IsSponsored'] = $marketsWithBTCAsBaseCurrencyRow->IsSponsored;
                    $data['LogoUrl'] = $marketsWithBTCAsBaseCurrencyRow->LogoUrl;
                    $data['created_at'] = Carbon::now();
                    $data['updated_at'] = Carbon::now();
                    $newlyListedMarketsDataRaw[] = $data;
                }
            }

            foreach ($newlyListedMarketsDataRaw as $row) {
                $marketName = $row['MarketName'];
                $marketListingDataRow = MarketListingModel::where('MarketName', '=', $marketName)->first();
                if (!$marketListingDataRow['isEmailed']) {
                    $newlyListedMarketsData[] = $row;
                }
            }

        } catch
        (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
        return $newlyListedMarketsData;
    }

    public function getOpenOrders($marketName = null)
    {
        $openOrders = [];
        try {
            $openOrders = $this->bittrexAPIs->getOpenOrders($marketName);
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
        return $openOrders;
    }

    public function cancel($orderUuid)
    {
        try {
            $this->bittrexAPIs->cancel($orderUuid);
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
        }
    }


    public function buyLimit($marketName, $quantity, $rate)
    {
        try {

            $bittrexAPIs = new BittrexAPIs($this->apiKey, $this->secretKey);
            $buyLimitResponse['status'] = $bittrexAPIs->buyLimit($marketName, $quantity, $rate);
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' Purchase details ' . serialize($buyLimitResponse['status']));

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'inputs' . serialize(Input::All()) . '   exception ' . $exception);
        }

    }


}