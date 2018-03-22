@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <h3 style="color: #23527c;">Add Company</h3>
                <br>

                <div class="panel panel-primary">
                    <div class="panel-body">

                        <form class="form-vertical" role="form" method="post" enctype="multipart/form-data" action="{{ route('post-add-company') }}">
                            <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                                <label for="company_name" class="control-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" id="company_name" value="{{ Request::old('company_name') ?: '' }}">
                                @if ($errors->has('company_name'))
                                    <span class="help-block">{{ $errors->first('company_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('company_info') ? ' has-error' : '' }}">
                                <label for="company_info" class="control-label">Company Info</label>
                                <textarea class="form-control" name="company_info" id="company_info" rows="6">{{ Request::old('company_info') ?: '' }}</textarea>
                                @if ($errors->has('company_info'))
                                    <span class="help-block">{{ $errors->first('company_info') }}</span>
                                @endif
                            </div>

                            <div>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Create Credentials (OAuth Client ID)</div>
                                    <div class="panel-body">
                                        1. Go to page <a target="_blank" href="http://developers.google.com/console">Google API console</a><br>
                                        2. Go to Dashboard -> Enabled Apis and Services -> Google Drive Api -> <b style="color:red">ENABLED</b><br>
                                        3. Credentials -> Create or Select Project<br>
                                        4. Credentials -> Create Credentials then pick <b>OAuth Client ID</b><br>
                                        5. In the section Application type pick <b>Other</b><br>
                                        6. Then download your <b>json client secret</b> file and upload here
                                    </div>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('client_secret') ? ' has-error' : '' }}">
                                <label class="control-label">Google Drive Client Secret</label>
                                <input type="file" class="form-control" placeholder='Choose a file...' name="client_secret"/>
                                @if ($errors->has('client_secret'))
                                    <span class="help-block">{{ $errors->first('client_secret') }}</span>
                                @endif
                            </div>

                            <div>
                                <p style="color: #c82333">You need to allow pop-up blocker in your browser</p>
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