<?php

namespace App\Classes;

use Log;
use App\Models\DailyMarketDataModel;
use App\Models\BotSettingsModel;
use App\Models\MarketPumpsModel;


class BittrexMarketOddsUtilities
{

    var $BTCPrefix = 'BTC-';
    var $supportPriceFactor = 1.1;
    var $resistancePriceFactor = 0.8;

    public function getExpectedValuesOfAllAltcoins()
    {
        $bittrexUtilities = new BittrexMarketUtilities(null, null);
        $marketSummaries = $bittrexUtilities->getMarketSummariesWithDailyPercentageReturns();

        $botSettingRow = BotSettingsModel::first();
        $period = $botSettingRow->historicalDataNumberOfDays;

        $trackers = [];
        if (count($marketSummaries)) {
            foreach ($marketSummaries as $row) {
                $marketName = $row->MarketName;
                if (strcasecmp($marketName, 'USDT-BTC') != 0) {
                    $altcoinHistoricalData = DailyMarketDataModel::where('marketName', '=', $marketName)->orderBy('created_at', 'DESC')->limit($period)->get();
                    if (count($altcoinHistoricalData) > 0) {
                        $expectedDataOfAltcoin = $this->getExpectedValue($marketName,$altcoinHistoricalData, $row->Last);
                        if (count($expectedDataOfAltcoin) > 0) {
                            $row = $this->mergeNestedMultiDimensionalAssociativeArrays($row, $expectedDataOfAltcoin);
                            $trackers[] = $row;
                        }
                    }
                } else {
                    $altcoinHistoricalData = DailyMarketDataModel::where('marketName', '=', $marketName)->orderBy('created_at', 'DESC')->limit($period)->get();
                    if (count($altcoinHistoricalData) > 0) {
                        $expectedDataOfAltcoin = $this->getUSDTBTCExpectedValue($altcoinHistoricalData, $row->Last);
                        $row = $this->mergeNestedMultiDimensionalAssociativeArrays($row, $expectedDataOfAltcoin);
                        $trackers[] = $row;
                    }
                }
            }
        }
        return $trackers;
    }

    public function getExpectedValue($marketName,$altcoinHistoricalData, $lastPrice)
    {
        $altcoinHistoricalData = json_decode(json_encode($altcoinHistoricalData), TRUE);

        usort($altcoinHistoricalData, function ($a, $b) {
            return $a['Last'] <=> $b['Last'];
        });

        $lowestPrice = $altcoinHistoricalData[0]['Last'];

        $numberOfRows = count($altcoinHistoricalData);

        $supportPriceData = $this->getSupportPrice($altcoinHistoricalData);
        $supportPriceConfidence = $supportPriceData->supportPriceConfidence;
        $needToCountForSupportPrice = $supportPriceData->needToCountForSupportPrice;
        $supportPrice = $supportPriceData->supportPrice;

        $equalOddsConfidence = 50 / 100; // 50% of the time prices are going to be below this price and 50% of the time prices are going to be above this price
        $needToCountForEqualOddsPrice = ceil($equalOddsConfidence * $numberOfRows); // Find location of row at which 50% of the time prices would be above this row price
        $equalOddsPrice = $altcoinHistoricalData[$needToCountForEqualOddsPrice - 1]['Last']; // 50% of the time prices are going to be above this price

        $resistancePriceData = $this->getResistancePrice($altcoinHistoricalData);
        $resistancePriceConfidence = $resistancePriceData->resistancePriceConfidence;
        $needToCountForResistancePrice = $resistancePriceData->needToCountForResistancePrice;
        $resistancePrice = $resistancePriceData->resistancePrice;

        $sumPrice = 0;
        $sumBaseVolume = 0;
        foreach ($altcoinHistoricalData as $row) {
            $sumPrice = $sumPrice + $row['Last'];
            $sumBaseVolume = $sumBaseVolume + $row['BaseVolume'];
        }
        $averagePrice = $sumPrice / $numberOfRows;
        $averageBaseVolume = $sumBaseVolume / $numberOfRows;

        $marketPumpsModel = MarketPumpsModel::where('marketName','=',$marketName )->get();
        $pumpCounts = count($marketPumpsModel);

        $result = (object)[];
        if ($supportPrice > 0) {
            $result->pumpCounts = $pumpCounts;
            $result->lowestPrice = $lowestPrice;
            $result->averagePrice = $averagePrice;
            $result->averageReturn = ($averagePrice - $supportPrice) / $supportPrice * 100;

            $result->expectedPrice = -$needToCountForSupportPrice / $numberOfRows * ($supportPrice - $lowestPrice) + $needToCountForEqualOddsPrice / $numberOfRows * ($equalOddsPrice - $supportPrice);
            $result->expectedReturn = $result->expectedPrice / $supportPrice * 100;

            $result->numberOfRows = $numberOfRows;
            $result->averageBaseVolume = $averageBaseVolume;

            $result->supportPriceConfidence = $supportPriceConfidence;
            $result->needToCountForSupportPrice = $needToCountForSupportPrice;
            $result->supportPrice = $supportPrice;

            $result->equalOddsConfidence = $equalOddsConfidence;
            $result->needToCountForEqualOddsPrice = $needToCountForEqualOddsPrice;
            $result->equalOddsPrice = $equalOddsPrice;
            $result->supportAndEqualOddsRatio = $equalOddsPrice / $supportPrice;

            $result->resistancePriceConfidence = $resistancePriceConfidence;
            $result->needToCountForResistancePrice = $needToCountForResistancePrice;
            $result->resistancePrice = $resistancePrice;
            $result->supportAndResistanceOddsRatio = $resistancePrice / $supportPrice;

            $result->supportNLastPercentageDifference = ($lastPrice - $supportPrice) / $supportPrice * 100;
            $result->resistanceNSupportPercentageDifference = ($resistancePrice - $supportPrice) / $supportPrice * 100;

        }
        return $result;
    }


    public function getSupportPrice($altcoinHistoricalData)
    {
        $lowestPrice = $altcoinHistoricalData[0]['Last'];
        $numberOfRows = count($altcoinHistoricalData);

        $supportPriceConfidence1 = 80 / 100; // 90% of the time prices are going to be above this price
        $needToCountForSupportPrice1 = ceil((1 - $supportPriceConfidence1) * $numberOfRows); // Find location of row at which 90% of the time prices would be above this row price
        $supportPrice1 = $altcoinHistoricalData[$needToCountForSupportPrice1 - 1]['Last']; // 90% of the time prices are going to be above this price

        $count = 0;
        $supportPrice2 = $lowestPrice;
        foreach ($altcoinHistoricalData as $row) {
            $lastPrice = $row['Last'];
            if ($lastPrice <= $lowestPrice * 1.10) {
                $count = $count + 1;
                $supportPrice2 = $lastPrice;
            } else {
                break;
            }
        }
        $supportPriceConfidence2 = $count / count($altcoinHistoricalData);
        $needToCountForSupportPrice2 = $count;

        if ($needToCountForSupportPrice2 > $needToCountForSupportPrice1) {
            $supportPriceConfidence = $supportPriceConfidence2;
            $needToCountForSupportPrice = $needToCountForSupportPrice2;
            $supportPrice = $supportPrice2;
        } else {
            $supportPriceConfidence = $supportPriceConfidence1;
            $needToCountForSupportPrice = $needToCountForSupportPrice1;
            $supportPrice = $supportPrice1;
        }

        $result = (object)[];
        if ($supportPrice > 0) {
            $result->supportPriceConfidence = $supportPriceConfidence;
            $result->needToCountForSupportPrice = $needToCountForSupportPrice;
            $result->supportPrice = $supportPrice;
        }else{
            $result->supportPriceConfidence = 0;
            $result->needToCountForSupportPrice = 0;
            $result->supportPrice = 0;
        }
        return $result;
    }


    public function getResistancePrice($altcoinHistoricalData)
    {
        $numberOfRows = count($altcoinHistoricalData);
        $highestPrice = $altcoinHistoricalData[$numberOfRows - 1]['Last'];

        $resistancePriceConfidence1 = 80 / 100; // 90% of the time prices are going to be below this price
        $needToCountForResistancePrice1 = ceil((1 - $resistancePriceConfidence1) * $numberOfRows); //  Find location of row at which 90% of the time prices would be below this row price
        $resistancePrice1 = $altcoinHistoricalData[$numberOfRows - $needToCountForResistancePrice1]['Last']; // 90% of the time prices are going to be below this price

        $count = 0;
        $resistancePrice2 = $highestPrice;
        for ($i = $numberOfRows - 1; $i >= 0; $i--) {
            $row = $altcoinHistoricalData[$i];
            $lastPrice = $row['Last'];
            if ($lastPrice >= $highestPrice * 0.90) {
                $count = $count + 1;
                $resistancePrice2 = $lastPrice;
            } else {
                break;
            }
        }
        $resistancePriceConfidence2 = $count / count($altcoinHistoricalData);
        $needToCountForResistancePrice2 = $count;

        if ($needToCountForResistancePrice2 > $needToCountForResistancePrice1) {
            $resistancePriceConfidence = $resistancePriceConfidence2;
            $needToCountForResistancePrice = $needToCountForResistancePrice2;
            $resistancePrice = $resistancePrice2;

        } else {
            $resistancePriceConfidence = $resistancePriceConfidence1;
            $needToCountForResistancePrice = $needToCountForResistancePrice1;
            $resistancePrice = $resistancePrice1;
        }

        $result = (object)[];
        if ($resistancePrice > 0) {
            $result->resistancePriceConfidence = $resistancePriceConfidence;
            $result->needToCountForResistancePrice = $needToCountForResistancePrice;
            $result->resistancePrice = $resistancePrice;
        }else{
            $result->resistancePriceConfidence = 0;
            $result->needToCountForResistancePrice = 0;
            $result->resistancePrice = 0;
        }
        return $result;
    }


    public function getUSDTBTCExpectedValue($altcoinHistoricalData, $lastPrice)
    {
        $altcoinHistoricalData = json_decode(json_encode($altcoinHistoricalData), TRUE);

        usort($altcoinHistoricalData, function ($a, $b) {
            return $a['Last'] <=> $b['Last'];
        });

        $lowestPrice = $altcoinHistoricalData[0]['Last'];

        $numberOfRows = count($altcoinHistoricalData);

        $supportPriceConfidence = 90 / 100; // 90% of the time prices are going to be above this price
        $needToCountForSupportPrice = ceil((1 - $supportPriceConfidence) * $numberOfRows); // Find location of row at which 90% of the time prices would be above this row price
        $supportPrice = $altcoinHistoricalData[$needToCountForSupportPrice - 1]['Last']; // 90% of the time prices are going to be above this price

        $equalOddsConfidence = 50 / 100; // 50% of the time prices are going to be below this price and 50% of the time prices are going to be above this price
        $needToCountForEqualOddsPrice = ceil($equalOddsConfidence * $numberOfRows); // Find location of row at which 50% of the time prices would be above this row price
        $equalOddsPrice = $altcoinHistoricalData[$needToCountForEqualOddsPrice - 1]['Last']; // 50% of the time prices are going to be above this price

        $resistancePriceConfidence = 90 / 100; // 90% of the time prices are going to be below this price
        $needToCountForResistancePrice = ceil((1 - $resistancePriceConfidence) * $numberOfRows); //  Find location of row at which 90% of the time prices would be below this row price
        $resistancePrice = $altcoinHistoricalData[$numberOfRows - $needToCountForResistancePrice]['Last']; // 90% of the time prices are going to be below this price

        $sumPrice = 0;
        $sumBaseVolume = 0;
        foreach ($altcoinHistoricalData as $row) {
            $sumPrice = $sumPrice + $row['Last'];
            $sumBaseVolume = $sumBaseVolume + $row['BaseVolume'];
        }
        $averagePrice = $sumPrice / $numberOfRows;
        $averageBaseVolume = $sumBaseVolume / $numberOfRows;

        $marketPumpsModel = MarketPumpsModel::where('marketName','=','USDT-BTC' )->get();
        $pumpCounts = count($marketPumpsModel);

        $result = (object)[];
        $result->pumpCounts = $pumpCounts;
        $result->averageBaseVolume = $averageBaseVolume;

        $result->lowestPrice = $lowestPrice;
        $result->averagePrice = $averagePrice;
        $result->averageReturn = ($averagePrice - $supportPrice) / $supportPrice * 100;

        $result->expectedPrice = -$needToCountForSupportPrice / $numberOfRows * ($supportPrice - $lowestPrice) + $needToCountForEqualOddsPrice / $numberOfRows * ($equalOddsPrice - $supportPrice);
        $result->expectedReturn = $result->expectedPrice / $supportPrice * 100;

        $result->numberOfRows = $numberOfRows;

        $result->supportPriceConfidence = $supportPriceConfidence;
        $result->needToCountForSupportPrice = $needToCountForSupportPrice;
        $result->supportPrice = $supportPrice;

        $result->equalOddsConfidence = $equalOddsConfidence;
        $result->needToCountForEqualOddsPrice = $needToCountForEqualOddsPrice;
        $result->equalOddsPrice = $equalOddsPrice;
        $result->supportAndEqualOddsRatio = $equalOddsPrice / $supportPrice;

        $result->resistancePriceConfidence = $resistancePriceConfidence;
        $result->needToCountForResistancePrice = $needToCountForResistancePrice;
        $result->resistancePrice = $resistancePrice;
        $result->supportAndResistanceOddsRatio = $resistancePrice / $supportPrice;

        $result->supportNLastPercentageDifference = ($lastPrice - $supportPrice) / $supportPrice * 100;
        $result->resistanceNSupportPercentageDifference = ($resistancePrice - $supportPrice) / $supportPrice * 100;

        return $result;
    }


    public function mergeNestedMultiDimensionalAssociativeArrays($firstArray, $secondArray)
    {
        $result = (object)[];
        foreach ($firstArray as $key => $value) {
            $result->$key = $value;
        }
        foreach ($secondArray as $key => $value) {
            $result->$key = $value;
        }
        return $result;
    }

}