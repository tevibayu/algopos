@extends ('backend.layouts.master')

@section ('title', trans('menus.role_management'))

@section('page-header')
    <h1>
        {{ trans('menus.user_management') }}
        <small>{{ trans('menus.role_management') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li>{!! link_to_route('admin.access.users.index', trans('menus.user_management')) !!}</li>
    <li class="active">{!! link_to_route('admin.access.roles.index', trans('menus.role_management')) !!}</li>
@stop

@section('content')
    @include('backend.access.includes.partials.header-buttons')
<div class="box box-warning">
    <div class="box-header with-border">
        <div class="col-sm-3" style="padding: 5px 0px 5px 0px;">
            {!! Form::open(['route' => 'admin.access.roles.index', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'get', 'id' => 'search']) !!}
            <div class="input-group">
                <input type="text" autocomplete="off" autofocus="" placeholder="{{ trans('strings.search') }}" class="form-control pull-right" value="{{ $search }}" name="search">
                <div class="input-group-btn">
                    <button class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ trans('crud.roles.role') }}</th>
            <th>{{ trans('crud.roles.permissions') }}</th>
            <th>{{ trans('crud.roles.login_destination') }}</th>
            <th>{{ trans('crud.roles.number_of_users') }}</th>
            <th class="column-manage">{{ trans('crud.actions') }}</th>
        </tr>
        </thead>
        <tbody>
            @if($roles->count())
                @foreach ($roles as $role)
                    <tr>
                        <td>{!! $role->sort !!}</td>
                        <td>{!! $role->name !!}</td>
                        <td>
                            @if ($role->all)
                                <span class="label label-success">All</span>
                            @else
                                @if (count($role->permissions) > 0)
                                    <div style="font-size:.7em">
                                        @foreach ($role->permissions as $permission)
                                            {!! $permission->display_name !!}<br/>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="label label-danger">None</span>
                                @endif
                            @endif
                        </td>
                        <td>{!! $role->login_destination !!}</td>
                        <td>{!! $role->users()->count() !!}</td>
                        <td>{!! $role->action_buttons !!}</td>
                    </tr>
                @endforeach
            @else
                <td colspan="6">{{ trans('strings.no_record') }}</td>
            @endif
        </tbody>
    </table>
        
    <div class="box-header">
        <div class="pull-left">
            {{ $roles->total() }} {{ trans('crud.roles.total') }}
        </div>
        <div class="pull-right">
            {!! $roles->appends(['search' => $search])->render() !!}
        </div>
    </div>

</div>
    
@if($roles->count())
    <script>
        var record_numb = {{ (($roles->currentPage()-1) * config('access.users.default_per_page')) + 1 }};
    </script>

    {!! HTML::script('public/js/backend/plugin/datatable/datatable_init.js') !!}
@endif

@stop