@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <h3 style="color: #23527c;">Complete Process...</h3>
                <br>

                <div class="panel panel-primary">
                    <div class="panel-body">

                        <div>
                            <p>
                                Go to <a target="_blank" href="{{ $token_url }}">this page</a> and paste token in field
                            </p>
                        </div>

                        <form class="form-vertical" role="form" method="post" action="{{ route('complete-company', [$company_id]) }}">
                            <div class="form-group{{ $errors->has('auth_token') ? ' has-error' : '' }}">
                                <label for="auth_token" class="control-label">Auth Token</label>
                                <input type="text" name="auth_token" class="form-control" id="auth_token" value="{{ Request::old('auth_token') ?: '' }}">
                                @if ($errors->has('auth_token'))
                                    <span class="help-block">{{ $errors->first('auth_token') }}</span>
                                @endif
                            </div>

                            <div class="form-gorup">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection