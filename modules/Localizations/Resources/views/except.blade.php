@extends ('backend.layouts.master')

@section ('title', trans('localizations::except.menus.index'))

@section('page-header')
    <h1>
        {{ trans('localizations::except.menus.index') }}
        <small>{{ trans('localizations::except.menus.view') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li class="active">{!! link_to_route('admin.localizations.except', trans('localizations::except.menus.index')) !!}</li>
@stop

@section('content')

<div class="box box-warning">
    <div class="box-header with-border">
        <div class="col-sm-9" style="padding: 5px 0px 5px 0px;">
            @permission($createPermissionExcept)
            <a class="btn btn-primary" href="{{ url('admin/localizations/except/create') }}">{{ trans('localizations::except.crud.button_create') }}</a>
            @endauth
        </div>
        <div class="col-sm-3" style="padding: 5px 0px 5px 0px;">
            {!! Form::open(['route' => 'admin.localizations.except', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'get', 'id' => 'search']) !!}
            <div class="input-group">
                <input type="text" autocomplete="off" autofocus="" placeholder="{{ trans('strings.search') }}" class="form-control pull-right" value="{{ $search }}" name="search">
                <div class="input-group-btn">
                    <button class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    @permission($deletePermissionExcept)
    {!! Form::open(['route' => 'admin.localizations.batch_delete_except', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'batch-delete']) !!}
    @endauth
        <table class="table table-striped table-bordered table-hover" style="margin-bottom: 0px;">
            <thead>
                <tr>
                    <th width="30px" class="column-check"><input type="checkbox" id="check_all" /></th>
                    <th width="25px">#</th>
                    <th>{{ trans('localizations::lang.crud.timezone') }}</th>
                    <th>{{ trans('localizations::lang.crud.module') }}</th>
                    <th>{{ trans('localizations::lang.crud.records') }}</th>
                    <th width="60px" class="column-manage">{{ trans('crud.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @if($records->count())
                    @foreach ($records as $record)
                    <tr>
                        <td>
                            @permission($deletePermissionExcept)
                            <input type="checkbox" name="checked[]" value="{!! $record->id_localization !!}" />
                            @endauth
                        </td>
                        <td></td>
                        <td>{!! access()->listTimeZone($record->timezone) !!}</td>
                        <td>{!! $record->module_name !!}</td>
                        <td>{!! $record->records !!}</td>
                        <td>
                            @permission($editPermissionExcept)
                            <a class="btn btn-xs btn-primary" href="{{ url('admin/localizations/except/edit/'.$record->id_localization) }}" data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('crud.edit_button') }}"><i title="" class="fa fa-pencil"></i></a>
                            @endauth
                            @permission($deletePermissionExcept)
                            <a href="{!! route('admin.localizations.delete_except', $record->id_localization) !!}" onclick="javascript:return confirm('{{ trans('crud.alert_delete') }}')" class="btn btn-xs btn-danger" data-toggle="tooltip" data-placement="top" title="{{ trans('crud.delete_button') }}"><i class="fa fa-trash"></i></a>
                            @endauth
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">{{ trans('strings.no_record') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if($records->count())
        @permission($deletePermissionExcept)
        <div class="box-header">
            <input type="submit" onclick="return confirm('{!! trans('crud.alert_batch_delete') !!}')" class="btn btn-danger" name="batch_delete" value="{!! trans('crud.delete_button') !!}" />
            <div class="pull-right">
                {!! $records->appends(['search' => $search])->render() !!}
            </div>
        </div>
        @endauth
        <div class="box-header" style="padding-top: 0px;">
            <div class="pull-left" id="total_records">
                {!! $records->total() !!} {{ trans('localizations::except.crud.total') }}
            </div>
        </div>
        @endif
    @permission($deletePermissionExcept)
    {!! Form::close() !!}
    @endauth
</div>

@if($records->count())
    <script>
        var record_numb = {!! $numb !!};
    </script>

    {!! HTML::script('public/js/backend/plugin/datatable/datatable_init.js') !!}
@endif

@stop