@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-6">

                <h3 style="color: #23527c;">Applying for job position: {{ $job_name }}</h3>
                <br>

                @if (isset($status))
                <div class="alert alert-{{ $status }}" role="alert">
                    <strong>{{ $msg }}</strong>
                </div>
                @endif

                <div class="panel panel-primary">
                    <div class="panel-body">


                            <form class="form-vertical" role="form" method="post" enctype="multipart/form-data" action="{{ route('post-apply-job', [$job_name]) }}">
                                <div class="form-group{{ $errors->has('fullname') ? ' has-error' : '' }}">
                                    <label for="fullname" class="control-label">Full Name</label>
                                    <input type="text" name="fullname" class="form-control" id="fullname" value="{{ Request::old('fullname') ?: '' }}">
                                    @if ($errors->has('fullname'))
                                        <span class="help-block">{{ $errors->first('fullname') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" value="{{ Request::old('email') ?: '' }}">
                                    @if ($errors->has('email'))
                                        <span class="help-block">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="phone" class="control-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" id="phone" value="{{ Request::old('phone') ?: '' }}">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('cv_file') ? ' has-error' : '' }}">
                                    <label class="control-label">CV</label>
                                        <input type="file" class="form-control" placeholder='Choose a file...' name="cv_file"/>
                                    @if ($errors->has('cv_file'))
                                        <span class="help-block">{{ $errors->first('cv_file') }}</span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('letter_file') ? ' has-error' : '' }}">
                                    <label class="control-label">Cover Letter</label>
                                        <input type="file" class="form-control" placeholder='Choose a file...' name="letter_file" />
                                    @if ($errors->has('letter_file'))
                                        <span class="help-block">{{ $errors->first('letter_file') }}</span>
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