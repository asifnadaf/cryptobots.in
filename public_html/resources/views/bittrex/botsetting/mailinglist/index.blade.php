@extends('layouts.index')

@section('content')
    <div id="page-wrapper">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Mailing List</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{URL::to('/')}}/mailinglistsetting/create" class="btn btn-primary">Add</a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full name</th>
                                    <th>Email address</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($settings) > 0)
                                    <?php $i = 1; ?>
                                    @forelse ($settings as $row)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $row->fullName }}</td>
                                            <td>{{ $row->emailAddress }}</td>
                                            <td><a href="{{URL::to('/')}}/mailinglistsetting/{{ $row->id }}/edit/">edit</a></td>
                                    @empty
                                        <tr>
                                            <td colspan="8">No mailing list found</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="8">No mailing list feed available. Please try again after
                                            sometime
                                        </td>
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
