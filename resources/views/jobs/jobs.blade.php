@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row" style="">

            <div class="col-lg-12" style="margin-bottom: 20px;">
                <form class="form-inline" role="form" method="get" action="">

                    <div class="col-lg-4" style="padding-left: 0px">
                        <div class="form-group" style="width: 100%">
                            @if(isset($search))
                                @php $text = $search @endphp
                            @else
                                @php $text = "" @endphp
                            @endif

                            <input type="text" style="width: 100%" name="search" class="form-control" id="search" placeholder="Position Name" value="{{ $text }}">
                        </div>
                    </div>

                    <div class="col-lg-4" style="padding-left: 0px">
                        <div class="form-gorup">
                                <button type="submit" class="btn btn-primary" style="/*margin-left: 10px*/">Search</button>
                        </div>
                    </div>

                    <div class="col-lg-4" style="padding-right: 0px">
                        <div class="form-gorup" align="right">
                            @if(\Illuminate\Support\Facades\Auth::check())
                                @if ( ! \Illuminate\Support\Facades\Auth::user()->company)
                                    <a href="{{ route('get-add-company') }}" class="btn btn-primary">Add Company</a>
                                @elseif (\Illuminate\Support\Facades\Auth::user()->company && \Illuminate\Support\Facades\Auth::user()->company->complete == 0)
                                    <a href="{{ route('get-complete-company', [\Illuminate\Support\Facades\Auth::user()->company->id]) }}" class="btn btn-primary">Complete Company</a>
                                @else
                                    <a href="{{ route('get-add-job') }}" class="btn btn-primary">Add New Job</a>
                                @endif
                            @endif
                       </div>
                    </div>

                </form>

                </div>

                <div class="col-lg-12" style="">

                    @foreach($jobs as $job)

                        <div class="panel panel-primary">
                            <div class="panel-body" style="padding-bottom: 0px">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <h3><a href="{{ route('job', [$job->name]) }}">{{ $job->name }}</a></h3>
                                    </div>

                                    <div class="col-lg-12">
                                        <h4><a href="{{ route('company-show', [$job->company->id]) }}">{{ $job->company->company_name }}</a></h4>
                                    </div>

                                    <div class="col-lg-12">
                                       <p> {{ $job->description }} </p>
                                    </div>

                                <div class="col-lg-2">

                                    @if(
                                      \Illuminate\Support\Facades\Auth::check() &&
                                      \Illuminate\Support\Facades\Auth::user()->company &&
                                      \Illuminate\Support\Facades\Auth::user()->company->id == $job->company->id
                                    )
                                        <a href="{{ route('get-edit-job', [$job->name]) }}" class="btn btn-primary"> Edit </a>
                                    @endif
                                </div>

                                <div class="col-lg-2 col-lg-offset-8">
                                    <div class="panel panel-danger">
                                        <div class="panel-body" style="padding: 6px;color: indianred;" align="center"> Deadline: {{ date("d.m.Y", strtotime($job->deadline)) }} </div>
                                    </div>
                                </div>
                                </div>
                                </div>

                            </div>
                    @endforeach

                    @if (count($jobs) == 0)
                        <div class="alert alert-warning" role="alert" align="center">
                            <strong>There is no job posts</strong>
                        </div>
                    @endif

                </div>

            <div class="col-lg-12 text-center">

                {{ $jobs->appends(\Illuminate\Support\Facades\Input::except('page'))->links() }}
            </div>

            </div>

    </div>

@endsection