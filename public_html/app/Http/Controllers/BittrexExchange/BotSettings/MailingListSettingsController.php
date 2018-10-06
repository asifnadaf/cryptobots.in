<?php

namespace App\Http\Controllers\BittrexExchange\BotSettings;

use App\Models\MailingListModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;


class MailingListSettingsController extends Controller
{

    public function index()
    {
        $settings = MailingListModel::get();
        return View::make('bittrex/arbitragesettings/mailinglist/index', compact('settings'));
    }

    public function create()
    {
        return View::make('bittrex/arbitragesettings/mailinglist/create');

    }

    public function store(Request $request)
    {
        $rules = array(
            'fullName' => 'required',
            'emailAddress' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('/mailinglistsetting/create')
                ->withErrors($validator)->withInput();
        } else {

            $fullName = Input::get('fullName');
            $emailAddress = Input::get('emailAddress');

            $setting = new MailingListModel();

            $setting->fullName = $fullName;
            $setting->emailAddress = $emailAddress;

            if ($setting->save()) {
                return Redirect::to('/mailinglistsetting');
            } else {
                $error = 'Error updating settings';
            }
            return View::make('mailinglistsetting/create', compact('error', 'setting'));
        }
    }

    public function edit($id)
    {
        $setting = MailingListModel::find($id);
        return View::make('bittrex/arbitragesettings/mailinglist/edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $rules = array(
            'fullName' => 'required',
            'emailAddress' => 'required',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('mailinglistsetting/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $fullName = Input::get('fullName');
            $emailAddress = Input::get('emailAddress');

            $setting = MailingListModel::find($id);

            $setting->fullName = $fullName;
            $setting->emailAddress = $emailAddress;

            if ($setting->save()) {
                return Redirect::to('/mailinglistsetting');
            } else {
                $error = 'Error updating settings';
            }

            $setting = MailingListModel::find($id);
            return View::make('mailinglistsetting/' . $id . '/edit', compact('error', 'setting'));

        }

    }

}
