@extends ('backend.layouts.master')

@section ('title', trans('menus.user_management') . ' | ' . trans('menus.banned_users'))

@section('page-header')
    <h1>
        {{ trans('menus.user_management') }}
        <small>{{ trans('menus.banned_users') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li>{!! link_to_route('admin.access.users.index', trans('menus.user_management')) !!}</li>
    <li class="active">{!! link_to_route('admin.access.users.banned', trans('menus.banned_users')) !!}</li>
@stop

@section('content')
    @include('backend.access.includes.partials.header-buttons')
<div class="box box-warning">
    <div class="box-header with-border">
        <div class="col-sm-3" style="padding: 5px 0px 5px 0px;">
            {!! Form::open(['route' => 'admin.access.users.banned', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'get', 'id' => 'search']) !!}
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
            <th>{{ trans('crud.users.name') }}</th>
            <th>{{ trans('crud.users.email') }}</th>
            <th>{{ trans('crud.users.confirmed') }}</th>
            <th>{{ trans('crud.users.roles') }}</th>
            <th>{{ trans('crud.users.other_permissions') }}</th>
            <th class="visible-lg">{{ trans('crud.users.created') }}</th>
            <th class="visible-lg">{{ trans('crud.users.last_updated') }}</th>
            <th class="column-manage">{{ trans('crud.actions') }}</th>
        </tr>
        </thead>
        <tbody>
            @if ($users->count())
                @foreach ($users as $user)
                    <tr>
                        <td>{!! $user->id !!}</td>
                        <td>{!! $user->name !!}</td>
                        <td>{!! link_to("mailto:".$user->email, $user->email) !!}</td>
                        <td>{!! $user->confirmed_label !!}</td>
                        <td>
                            @if ($user->roles()->count() > 0)
                                @foreach ($user->roles as $role)
                                    {!! $role->name !!}<br/>
                                @endforeach
                            @else
                                None
                            @endif
                        </td>
                        <td>
                            @if ($user->permissions()->count() > 0)
                                @foreach ($user->permissions as $perm)
                                    {!! $perm->display_name !!}<br/>
                                @endforeach
                            @else
                                None
                            @endif
                        </td>
                        <td class="visible-lg">{!! $user->created_at->diffForHumans() !!}</td>
                        <td class="visible-lg">{!! $user->updated_at->diffForHumans() !!}</td>
                        <td>{!! $user->action_buttons !!}</td>
                    </tr>
                @endforeach
            @else
                <td colspan="9">{{ trans('crud.users.no_banned_users') }}</td>
            @endif
        </tbody>
    </table>

    <div class="box-header">
        <div class="pull-left">
            {!! $users->total() !!} {{ trans('crud.users.total') }}
        </div>
        <div class="pull-right">
            {!! $users->appends(['search' => $search])->render() !!}
        </div>
    </div>

</div>
    
@if($users->count())
    <script>
        var record_numb = {{ (($users->currentPage()-1) * config('access.users.default_per_page')) + 1 }};
    </script>

    {!! HTML::script('public/js/backend/plugin/datatable/datatable_init.js') !!}
@endif

@stop