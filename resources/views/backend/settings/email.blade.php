@extends ('backend.layouts.master')

@section ('title', trans('menus.email_settings'))

@section('page-header')
    <h1>
        {{ trans('menus.email_settings') }}
        <small>{{ trans('menus.email_settings') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li class="active">{!! link_to_route('admin.settings.email', trans('menus.email_settings')) !!}</li>
@stop

@section('content')

<div class="box box-warning">
    <div class="panel-body">
        {!! Form::open(['route' => 'admin.settings.save_email_settings', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'email-settings']) !!}
            <div class="form-group">
                {!! Form::label('driver', trans('crud.settings.driver'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-6">
                    {!! Form::text('driver', $records['mail.driver'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.driver')]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('host', trans('crud.settings.host'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-6">
                    {!! Form::text('host', $records['mail.host'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.host')]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('port', trans('crud.settings.port'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-4">
                    {!! Form::text('port', $records['mail.port'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.port')]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('username', trans('crud.settings.username'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-6">
                    {!! Form::text('username', $records['mail.username'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.username')]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('password', trans('crud.settings.password'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-6">
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('crud.settings.password')]) !!}
                    <span class="help-block" style="font-size: 10px; margin-bottom: 0px;">{{ trans('crud.settings.password_desc') }}</span>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('email_name', trans('crud.settings.email_name'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-6">
                    {!! Form::text('email_name', $records['mail.from.name'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.email_name')]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('email_address', trans('crud.settings.email_address'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-6">
                    {!! Form::text('email_address', $records['mail.from.address'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.email_address')]) !!}
                </div>
            </div>
            <div style="margin-bottom: 5px;" class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <input type="submit" value="{{ trans('strings.save_button') }}" class="btn btn-primary" name="save">
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>

@stop