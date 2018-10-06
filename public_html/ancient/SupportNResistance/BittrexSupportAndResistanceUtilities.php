<?php

namespace App\Classes;

use Log;
use App\Models\DailyMarketDataModel;
use App\Models\BotSettingsModel;


class BittrexSupportAndResistanceUtilities
{
    var $supportPriceFactor = 1.1;
    var $resistancePriceFactor = 0.8;

    public function getSupportAndResistancePriceOfAllAltcoins()
    {

        $bittrexUtilities = new BittrexMarketUtilities(null, null);
        $marketSummaries = $bittrexUtilities->getMarketSummariesWithDailyPercentageReturns();

        $botSettingRow = BotSettingsModel::first();
        $period = $botSettingRow->historicalDataNumberOfDays;

        $trackers = [];
        if (count($marketSummaries)) {
            foreach ($marketSummaries as $row) {
                $marketName = $row->MarketName;
                $altcoinHistoricalData = DailyMarketDataModel::where('marketName', '=', $marketName)->orderBy('created_at', 'DESC')->limit($period)->get();

                if (count($altcoinHistoricalData) > 0) {
                    $supportPriceOfAltcoin = $this->supportPriceOfAltcoin($altcoinHistoricalData, $row->Last);
                    $resistancePriceOfAltcoin = $this->resistancePriceOfAltcoin($altcoinHistoricalData, $row->Last);

                    $mergedData = $this->mergeNestedMultiDimensionalAssociativeArrays($supportPriceOfAltcoin, $resistancePriceOfAltcoin);
                    $row = $this->mergeNestedMultiDimensionalAssociativeArrays($row, $mergedData);
                    $trackers [] = $row;
                }
            }
        }
        return $trackers;
    }

    public function supportPriceOfAltcoin($altcoinHistoricalData, $lastPrice)
    {
        $minValue = $this->minValue($altcoinHistoricalData);
        $supportThreshold = $minValue * $this->supportPriceFactor;
        $sumOfSupportPriceWithRange = 0;
        $supportCount = 0;
        $totalSupportCount = 0;
        foreach ($altcoinHistoricalData as $row) {
            $low = $row->Low;
            $totalSupportCount++;
            if ($low <= $supportThreshold) {
                $sumOfSupportPriceWithRange = $sumOfSupportPriceWithRange + $low;
                $supportCount++;
            }
        }
        $averageSupportPrice = $sumOfSupportPriceWithRange / $supportCount;
        $result = (object)[];
        $result->averageSupportPrice = $averageSupportPrice;
        $result->supportCount = $supportCount;
        $result->totalSupportCount = $totalSupportCount;
        $result->supportStrength = $supportCount / $totalSupportCount * 100;
        $result->minValue = $minValue;
        $result->supportThreshold = $supportThreshold;
        $result->avgSupportNLastdifference = ($lastPrice - $averageSupportPrice) / $averageSupportPrice * 100;

        return $result;
    }


    public function minValue($altcoinHistoricalData)
    {
        $minValue = 0;
        $count = 0;
        foreach ($altcoinHistoricalData as $row) {
            $low = $row->Low;
            if ($count === 0) {
                $minValue = $low;
            }
            $count++;

            if ($low < $minValue) {
                $minValue = $low;
            }
        }
        return $minValue;
    }


    public function resistancePriceOfAltcoin($altcoinHistoricalData, $lastPrice)
    {
        $maxValue = $this->maxValue($altcoinHistoricalData);
        $resistanceThreshold = $maxValue * $this->resistancePriceFactor;
        $sumOfResistancePriceWithinRange = 0;
        $resistanceCount = 0;
        $totalResistanceCount = 0;

        foreach ($altcoinHistoricalData as $row) {
            $last = $row->Last;
            $totalResistanceCount++;
            if ($last >= $resistanceThreshold) {
                $sumOfResistancePriceWithinRange = $sumOfResistancePriceWithinRange + $last;
                $resistanceCount++;
            }
        }

        $averageResistancePrice = $sumOfResistancePriceWithinRange / $resistanceCount;
        $result = (object)[];
        $result->averageResistancePrice = $averageResistancePrice;
        $result->resistanceCount = $resistanceCount;
        $result->totalResistanceCount = $totalResistanceCount;
        $result->resistanceStrength = $resistanceCount / $totalResistanceCount * 100;
        $result->maxValue = $maxValue;
        $result->resistanceThreshold = $resistanceThreshold;
        $result->avgResistanceNLastdifference = ($averageResistancePrice - $lastPrice) / $lastPrice * 100;

        return $result;
    }

    public function maxValue($altcoinHistoricalData)
    {
        $maxValue = 0;
        $count = 0;
        foreach ($altcoinHistoricalData as $row) {
            $open = $row->Last;
            if ($count === 0) {
                $maxValue = $open;
            }
            $count++;

            if ($open > $maxValue) {
                $maxValue = $open;
            }
        }
        return $maxValue;
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