@extends('backend.layouts.master')

@section('page-header')
    <h1>
        {{ app_name() }}
        <small>{{ trans('labels.change_password_box_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="#"><i class="fa fa-laptop"></i> {{ trans('menus.title.settings') }}</a></li>
    <li class="active">{{ trans('labels.change_password_box_title') }}</li>
@endsection

@section('content')
<div class="tab-content">
    <div class="box box-warning">
        <div class="panel-body">
            <div class="panel-body">

                {!! Form::open(['route' => ['password.change'], 'class' => 'form-horizontal']) !!}

                    <div class="form-group">
                        {!! Form::label('old_password', trans('validation.attributes.old_password'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::input('password', 'old_password', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('password', trans('validation.attributes.new_password'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('password_confirmation', trans('validation.attributes.new_password_confirmation'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            {!! Form::submit(trans('labels.change_password_button'), ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>

                {!! Form::close() !!}

            </div><!--panel body-->
        </div>
    </div>
</div>
@endsection