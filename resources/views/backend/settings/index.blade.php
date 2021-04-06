@extends ('backend.layouts.master')

@section ('title', trans('menus.general_settings'))

@section('page-header')
    <h1>
        {{ trans('menus.general_settings') }}
        <small>{{ trans('menus.general_settings') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li class="active">{!! link_to_route('admin.settings.general', trans('menus.general_settings')) !!}</li>
@stop

@section('content')

<div class="box box-warning">
    <div class="panel-body">
        {!! Form::open(['route' => 'admin.settings.save_general_settings', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'general-settings']) !!}
            <div class="form-group">
                {!! Form::label('site_name', trans('crud.settings.site_name'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-8">
                    {!! Form::text('site_name', $records['app.name'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.site_name')]) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('default_per_page', trans('crud.settings.default_per_page'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-4">
                    {!! Form::text('default_per_page', $records['access.users.default_per_page'], ['class' => 'form-control', 'placeholder' => trans('crud.settings.default_per_page')]) !!}
                    <span class="help-block" style="font-size: 10px; margin-bottom: 0px;">{{ trans('crud.settings.default_per_page_desc') }}</span>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('debug', trans('crud.settings.debug'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-4">
                    {!! Form::select('debug', ['1' => 'Yes', '0' => 'No'], $records['app.debug'], ['class' => 'form-control']) !!}
                    <span class="help-block" style="font-size: 10px; margin-bottom: 0px;">{{ trans('crud.settings.debug_desc') }}</span>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('profiler', trans('crud.settings.profiler'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-4">
                    {!! Form::select('profiler', ['1' => 'Yes', '0' => 'No'], $records['app.profiler'], ['class' => 'form-control']) !!}
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