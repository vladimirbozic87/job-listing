@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <h3 style="color: #23527c;">{{ $company->company_name }}</h3>
                <br>

                @if (isset($status))
                    <div class="alert alert-{{ $status }}" role="alert">
                        <strong>{{ $msg }}</strong>
                    </div>
                @endif

                @if (\Illuminate\Support\Facades\Auth::user() && \Illuminate\Support\Facades\Auth::user()->company)
                    @if (\Illuminate\Support\Facades\Auth::user()->company->id === $company->id)
                        @php $company_own = true; @endphp
                    @else
                        @php $company_own = false; @endphp
                    @endif
                @else
                    @php $company_own = false; @endphp
                @endif

                <div class="panel panel-primary">
                    <div class="panel-body">

                        <form class="form-vertical" role="form" method="post" action="{{ route('company-edit', [$company->id]) }}">

                            <div class="form-group{{ $errors->has('company_info') ? ' has-error' : '' }}">
                                <label for="company_info" class="control-label">Company Info</label>
                                <textarea class="form-control" name="company_info" @if ( ! $company_own) readonly @endif id="company_info" rows="6">{{ Request::old('company_info') ?: $company->company_info }}</textarea>
                                @if ($errors->has('company_info'))
                                    <span class="help-block">{{ $errors->first('company_info') }}</span>
                                @endif
                            </div>

                            @if ($company_own)
                            <div class="form-gorup">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                            @endif
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection