@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Buy Settings</h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{URL::to('/')}}/buysetting/{{ $settings->id }}/edit" class="btn btn-primary">Edit</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td>Support and equal odds ratio</td>
                                    <td>{{ $settings->supportAndEqualOddsRatio }}</td>
                                </tr>
                                <tr>
                                    <td>Maximum number of diversification</td>
                                    <td>{{ $settings->maximumNumberOfDiversification }}</td>
                                </tr>
                                <tr>
                                    <td>Minimum base currency volume (BTC)</td>
                                    <td>{{ $settings->minimumVolumeOfBaseCurrencyBTC }}</td>
                                </tr>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@stop
