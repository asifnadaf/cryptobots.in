<?php

namespace App\Classes;

use Log;
use Mail;
use App\Models\MailingListModel;

class EmailRecipientsUtilities
{
    var $className = null;

    public function __construct()
    {
        $this->className = (new \ReflectionClass($this))->getShortName();
    }

    public function getRecipientsAddresses()
    {
        try {
            $data = MailingListModel::select('emailAddress')->get();
            $emailRecipients = [];
            foreach ($data as $item) {
                $emailRecipients [] = $item->emailAddress;
            }
            return $emailRecipients;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }


    public function getRecipientAddresses()
    {
        try {
            $data = MailingListModel::select('emailAddress')->first();
            $emailRecipients [] = $data->emailAddress;
            return $emailRecipients;

        } catch (Exception $exception) {
            Log::info(get_class($this) . '->' . __FUNCTION__ . ' exception: ' . 'exception' . $exception);
        }
    }

}