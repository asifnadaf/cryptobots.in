@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sell Settings</h1>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{URL::to('/')}}/sellsetting/{{ $settings->id }}/edit" class="btn btn-primary">Edit</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td>Pump factor</td>
                                    <td>{{ $settings->pumpFactor }}x</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop
