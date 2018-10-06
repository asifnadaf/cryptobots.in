<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\BotRunningStatusModel;
use Log;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class BotRunningStatusController extends Controller
{

    public function index()
    {

        $botRunningStatusData = [];
        try {
            $botRunningStatusData = BotRunningStatusModel::orderBy('sequenceNo', 'asc')->get();
            $accountError = '';
            foreach ($botRunningStatusData as $row) {
                $row->indicator = 'black';
                if (strcasecmp($row->runsEvery, 'Every minute') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $sixtysecondsPlus10SecondsBack = Carbon::now()->subSeconds(70);
                    $timeDifference = $sixtysecondsPlus10SecondsBack->diffInSeconds($lastRun, false);


                    if ($timeDifference < 0) {
                        $row->indicator = 'red';
                    }
                }
                if (strcasecmp($row->runsEvery, 'Every 5 minutes') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $fiveMinutesPlus10SecondsBack = Carbon::now()->subSeconds(310);
                    $timeDifference = $fiveMinutesPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $row->indicator = 'red';
                    }
                }

                if (strcasecmp($row->runsEvery, 'Every 10 minutes') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $tenMinutesPlus10SecondsBack = Carbon::now()->subSeconds(610);
                    $timeDifference = $tenMinutesPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $row->indicator = 'red';
                    }
                }

                if (strcasecmp($row->runsEvery, 'Every 30 minutes') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $thirtyMinutesPlus10SecondsBack = Carbon::now()->subSeconds(1810);
                    $timeDifference = $thirtyMinutesPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $row->indicator = 'red';
                    }
                }

                if (strcasecmp($row->runsEvery, 'Every three hours between 7am to 10pm') == 0) {

                    $startDateTime = Carbon::now();
                    $startDateTime->setTime(07, 00, 00);
                    $endDateTime = Carbon::now();
                    $endDateTime->setTime(21, 15, 30);

                    $isTrue = Carbon::now()->between($startDateTime, $endDateTime);

                    if ($isTrue) {
                        $lastRun = $row->lastRun;
                        $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                        $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                        $threeHoursPlus10SecondsBack = Carbon::now()->subSeconds(10810);
                        $timeDifference = $threeHoursPlus10SecondsBack->diffInSeconds($lastRun, false);
                        if ($timeDifference < 0) {
                            $row->indicator = 'red';
                        }
                    }

                }

                if (strcasecmp($row->runsEvery, 'dailyAt(\'10:58\')') == 0 || strcasecmp($row->runsEvery, 'dailyAt(\'11:00\')') == 0 || strcasecmp($row->runsEvery, 'dailyAt(\'00:01\')') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $twentyFourHoursPlus10SecondsBack = Carbon::now()->subSeconds(86410);
                    $timeDifference = $twentyFourHoursPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $row->indicator = 'red';
                    }
                }
            }
        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            $accountError = $exception;
        }
        return View::make('bittrex/botrunningstatus/index', compact('botRunningStatusData', 'accountError'));
    }


}
