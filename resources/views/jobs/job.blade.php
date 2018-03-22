@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                @if (isset($status))
                    <div class="alert alert-{{ $status }}" role="alert">
                        <strong>{{ $msg }}</strong>
                    </div>
                @endif

                <div class="panel panel-primary">
                    <div class="panel-body">

                        <div>
                            <h3 align="center" style="color: #23527c;"> {{ $job->name }} </h3>
                            <br>

                            <div>
                                <h4 align="center" style="color: #23527c;"> {{ $job->company->company_name }} </h4>
                                <div class="form-group">
                                    <textarea class="form-control" name="description" id="description" readonly rows="6">{{ $job->company->company_info }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" name="description" id="description" readonly rows="15">{{ $job->description }}</textarea>
                            </div>
                            <div class="panel panel-danger">
                                <div class="panel-body" style="padding: 6px;color: indianred;" align="center"> Deadline: {{ date("d.m.Y", strtotime($job->deadline)) }} </div>
                            </div>
                        </div>

                        @if( ! Auth::check())

                           <a href="{{ route('get-apply-job', [$job->name]) }}" class="btn btn-primary">Apply</a>

                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection