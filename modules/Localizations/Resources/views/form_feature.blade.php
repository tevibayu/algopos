@extends ('backend.layouts.master')

@section ('title', $title)

@section('page-header')
    <h1>
        {{ trans('localizations::feature.menus.index') }}
        <small>{{ $title }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li>{!! link_to_route('admin.localizations.feature', trans('localizations::feature.menus.index')) !!}</li>
    <li class="active">{!! $my_link !!}</li>
@stop

@section('content')

<div class="box box-warning">
    <div class="panel-body">
        @if($form_type == 'create')
        {!! Form::open(['route' => 'admin.localizations.create_feature', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create']) !!}
        @elseif($form_type == 'edit')
        {!! Form::model($record, ['route' => ['admin.localizations.edit_feature', $record->id_localization], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'edit']) !!}
        @endif
            <div class="form-group">
                {!! Form::label('timezone', trans('localizations::lang.crud.timezone'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-6">
                    {!! Form::select('timezone', ['' => ''] + $timezone, null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('records', trans('localizations::lang.crud.module'), ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::select('records[]', $permissions, $form_type == 'edit' ? $feature_records : null, ['class' => 'form-control', 'multiple' => '']) !!}
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

{!! HTML::style('public/plugin/select2/select2.css') !!}
{!! HTML::style('public/plugin/select2/select2-bootstrap.css') !!}
{!! HTML::script('public/plugin/select2/select2.min.js') !!}

<script>
    var select_timezone = "{{ trans('localizations::lang.crud.select_timezone') }}";
    var select_module = "{{ trans('localizations::lang.crud.select_module') }}";
</script>

{!! HTML::script(Module::asset('Localizations:Assets/js/form_feature.js')) !!}

@stop