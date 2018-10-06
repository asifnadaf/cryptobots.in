@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <h1 class="page-header">Altcoins Settings</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{URL::to('/')}}/altcoinssettings/create" class="btn btn-primary">Add</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Exchange name</th>
                                    <th>Market name</th>
                                    <th>Is buying paused?</th>
                                    <th>Is selling on resistance paused?</th>
                                    <th>Is selling at 2X paused?</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($altcoinsSettingsData) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($altcoinsSettingsData as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $row->exchangeName }}</td>
                                            <td>
                                                <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row->marketName }}"
                                                   target="_blank">{{ $row->marketName }}</a>
                                            </td>
                                            <td>{{ $row->isBuyingPaused }}</td>
                                            <td>{{ $row->isSellingOnResistancePaused }}</td>
                                            <td>{{ $row->isSellingAt2XPaused }}</td>
                                            <td>
                                                <a href="{{URL::to('/')}}/altcoinssettings/{{ $row->id }}/edit">edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">No altcoins settings found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No altcoins settings. Please try again after sometime.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@stop
