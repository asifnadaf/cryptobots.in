<?php

namespace App\Http\Controllers\BittrexExchange;

use App\Models\PastInvestmentsOpportunitiesModel;
use Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PastInvestmentsOpportunitiesController extends Controller
{

    public function index()
    {

        $supportPricesCurrencies = PastInvestmentsOpportunitiesModel::where('orderType', '=', 'Buy')->orderBy('updated_at', 'desc')->get();
        $resistancePricesCurrencies = PastInvestmentsOpportunitiesModel::where('orderType', '=', 'Sell')->orderBy('updated_at', 'desc')->get();

        return View::make('bittrex/pastinvestmentsopportunities/index', compact('supportPricesCurrencies', 'resistancePricesCurrencies'));
    }


    public function edit($id)
    {
        $resistancePrice = PastInvestmentsOpportunitiesModel::find($id);
        $isSellingOnResistancePaused = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );

        return View::make('bittrex/pastinvestmentsopportunities/edit', compact('resistancePrice', 'isSellingOnResistancePaused'));

    }

    public function update(Request $request, $id)
    {

        $rules = array(
            'isSellingOnResistancePaused' => 'required|not_in:0',
        );

        $id = Input::get('resistancePriceId');

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // get the error messages from the validator
            return Redirect::to('opportunities/' . $id . '/edit')
                ->withErrors($validator)->withInput();
        } else {

            $isSellingOnResistancePaused = Input::get('isSellingOnResistancePaused');

            $resistancePrice = PastInvestmentsOpportunitiesModel::find($id);
            $resistancePrice->isSellingOnResistancePaused = $isSellingOnResistancePaused;

            if ($resistancePrice->save()) {
                return Redirect::to('/opportunities');
            } else {
                $error = 'Error updating opportunities price';
            }

            $resistancePrice = PastInvestmentsOpportunitiesModel::find($id);
            $isSellingOnResistancePaused = array(
                'Yes' => 'Yes',
                'No' => 'No'
            );
            return View::make('bittrex/support/edit', compact('error', 'resistancePrice', 'isSellingOnResistancePaused'));

        }
    }

}
