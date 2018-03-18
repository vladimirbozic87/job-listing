@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-primary">
                    <div class="panel-body">

                        <form class="form-vertical" role="form" method="post" action="{{ route('post-add-job') }}">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="control-label">Position Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ Request::old('name') ?: '' }}">
                                @if ($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description" class="control-label">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="15">{{ Request::old('description') ?: '' }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">{{ $errors->first('description') }}</span>
                                @endif
                            </div>


                            <!--<div class="row">-->
                            <div class="form-group{{ $errors->has('deadline') ? ' has-error' : '' }}">
                                <label class="control-label" for="deadline">Deadline</label>
                                <div class='input-group date'>
                                    <input class="form-control" id="deadline" name="deadline" placeholder="MM/DD/YYY" type="text" value="{{ Request::old('deadline') ?: '' }}"/>
                                    <span class="input-group-addon">
                       <span id="datetimepiker" style="position:absolute"></span>
                       <span class="glyphicon glyphicon-calendar"></span>
                     </span>
                                </div>
                                @if ($errors->has('date'))
                                    <span class="help-block">{{ $errors->first('deadline') }}</span>
                                @endif
                                <script>
                                    $(document).ready(function(){
                                        var date_input=$('input[name="deadline"]');
                                        var container= "#datetimepiker";
                                        var options={
                                            format: 'dd.mm.yyyy',
                                            container: container,
                                            todayHighlight: true,
                                            autoclose: true,
                                        };
                                        date_input.datepicker(options);
                                    })
                                </script>
                            </div>
                            <!--</div>-->


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