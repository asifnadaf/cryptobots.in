@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header text-capitalize">Bot Running Status</h1>
            </div>
        </div>

        <div class="row">
            @if($accountError!=null)
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    {{$accountError}}
                </div>
        @endif

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="text-left">#</th>
                                    <th class="text-left">Bot name</th>
                                    <th class="text-left">Runs @</th>
                                    <th class="text-left">Last run</th>
                                    <th class="text-left">DB affected</th>
                                    <th class="text-left">Latest DB update timestamp</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($botRunningStatusData) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($botRunningStatusData as $row)
                                        <tr style="color: {{ $row['indicator'] }}">
                                            <td class="text-left">{{ $i++ }}</td>
                                            <td class="text-left">{{ $row['className'] }} </td>
                                            <td class="text-left">{{ $row['runsEvery'] }} </td>
                                            <td class="text-left">{{Carbon\Carbon::parse($row['lastRun'],'UCT')->format('d-m-Y H:i:s A') }}</td>
                                            @if($row['dbAffected']!=null)
                                                <td class="text-left">{{ $row['dbAffected'] }} </td>
                                            @else
                                                <td class="text-left">None</td>
                                            @endif
                                            @if($row['dbLatestUpdatesTimestamp']!=null)
                                                <td class="text-left">{{Carbon\Carbon::parse($row['dbLatestUpdatesTimestamp'],'UCT')->format('d-m-Y H:i:s A') }}</td>
                                                @else
                                                <td class="text-left">Not applicable</td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">You have no account balance.</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="10">No account balance found. Please try again after sometime.</td>
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
