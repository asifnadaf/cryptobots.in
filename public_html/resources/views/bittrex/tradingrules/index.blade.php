@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <h1 class="page-header">Crypto currencies Trading Rules</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Do's</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Rule</th>
                                    <th>Reasons</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1; ?>
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>When BTCs rate are high, Convert it into USDTs.</td>
                                    <td>When BTCs rate go down again, you will be able to purchase more BTCs with the same USDTs.</td>
                                </tr>
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>When BTCs rate are low, Convert your USDTs into BTCs and convert your BTCs into Altcoins near support price.</td>
                                    <td>When your altcoins go up again, you get more BTCs. When BTCs rate go up again, your will get more USDTs.</td>
                                </tr>
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>Buy BTCs from row at some discount when BTCs are at the highest rate and convert them into USDTs</td>
                                    <td>When BTCs rate go down again, you will be able to purchase more BTCs with the same USDTs.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@stop
