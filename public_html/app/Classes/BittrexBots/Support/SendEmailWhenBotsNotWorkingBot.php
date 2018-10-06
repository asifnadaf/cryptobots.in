<?php

namespace App\Classes\BittrexBots\Support;

use App\Models\BotRunningStatusModel;
use App\Classes\EmailRecipientsUtilities;
use Log;
use Mail;
use Carbon\Carbon;
use Mockery\Exception;

class SendEmailWhenBotsNotWorkingBot
{

    var $className = null;
    var $bittrexMinimumInvestmentLimits = 0.00050000;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function runBot()
    {

        $botRunningStatusData = [];
        try {
            $botRunningStatusData = BotRunningStatusModel::orderBy('sequenceNo', 'asc')->get();
            $accountError = '';
            foreach ($botRunningStatusData as $row) {
                if (strcasecmp($row->runsEvery, 'Every minute') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $sixtysecondsPlus10SecondsBack = Carbon::now()->subSeconds(70);
                    $timeDifference = $sixtysecondsPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $this->checkAndSendEmailNotification($row);
                    }
                }
                if (strcasecmp($row->runsEvery, 'Every 5 minutes') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $fiveMinutesPlus10SecondsBack = Carbon::now()->subSeconds(310);
                    $timeDifference = $fiveMinutesPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $this->checkAndSendEmailNotification($row);
                    }
                }

                if (strcasecmp($row->runsEvery, 'Every 10 minutes') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $tenMinutesPlus10SecondsBack = Carbon::now()->subSeconds(610);
                    $timeDifference = $tenMinutesPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $this->checkAndSendEmailNotification($row);
                    }
                }

                if (strcasecmp($row->runsEvery, 'Every 30 minutes') == 0) {
                    $lastRun = $row->lastRun;
                    $lastRun = Carbon::parse($lastRun, 'UCT')->format('d-m-Y G:i:s');
                    $lastRun = Carbon::createFromFormat('d-m-Y G:i:s', $lastRun);

                    $thirtyMinutesPlus10SecondsBack = Carbon::now()->subSeconds(1810);
                    $timeDifference = $thirtyMinutesPlus10SecondsBack->diffInSeconds($lastRun, false);
                    if ($timeDifference < 0) {
                        $this->checkAndSendEmailNotification($row);
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
                            $this->checkAndSendEmailNotification($row);
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
                        $this->checkAndSendEmailNotification($row);
                    }
                }
            }

            $botRunningStatusData = BotRunningStatusModel::where('className', '=', $this->className)->first();
            $botRunningStatusData->lastRun = Carbon::now();
            $botRunningStatusData->runsEvery = 'Every 10 minutes';
            $botRunningStatusData->save();

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . $exception);
            $accountError = $exception;
        }

    }


    public function checkAndSendEmailNotification($row)
    {
        $lastErrorEmailSentTimestamp = $row->lastErrorEmailSentTimestamp;
        if ($lastErrorEmailSentTimestamp != null) {
            $lastErrorEmailSentTimestamp = Carbon::parse($lastErrorEmailSentTimestamp, 'UCT')->format('d-m-Y G:i:s');
            $lastErrorEmailSentTimestamp = Carbon::createFromFormat('d-m-Y G:i:s', $lastErrorEmailSentTimestamp);

            $twentyFourHoursPlus10SecondsBack = Carbon::now()->subSeconds(86410);
            $timeDifference = $twentyFourHoursPlus10SecondsBack->diffInSeconds($lastErrorEmailSentTimestamp, false);
            if ($timeDifference < 0) {
                $this->sendEmailNotification($row);
            }
        }
    }

    public function sendEmailNotification($row)
    {
        Log::info(get_class($this) . '->' . __FUNCTION__ . ' $row: ' . $row->className);

        try {
            $mailBody['botData'] = $row;

            $emailRecipientsUtilities = new EmailRecipientsUtilities();
            $emailRecipients = $emailRecipientsUtilities->getRecipientsAddresses();

            $mailData = array(
                'email' => $emailRecipients,
                'from_name' => 'CryptoBots',
                'from' => 'info@cryptobots.in',
                'subject' => 'Bot not working',
                'mailBody' => $mailBody
            );

            Mail::send('bittrex.emails.SendEmailWhenBotsNotWorkingBot.mail_body', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'])->from($mailData['from'], $mailData['from_name'])->subject($mailData['subject']);
            });

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }

        $row->lastErrorEmailSentTimestamp = Carbon::now();
        $row->save();
    }


}