@extends ('backend.layouts.master')

@section ('title', trans('menus.permission_management') . ' | ' . trans('menus.create_permission_group'))

@section('page-header')
    <h1>
        {{ trans('menus.permission_management') }}
        <small>{{ trans('menus.create_permission_group') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li>{!! link_to_route('admin.access.users.index', trans('menus.user_management')) !!}</li>
    <li>{!! link_to_route('admin.access.roles.permissions.index', trans('menus.permission_management')) !!}</li>
    <li class="active">{!! link_to_route('admin.access.roles.permission-group.create', trans('menus.create_permission_group')) !!}</li>
@stop

@section('content')

    @include('backend.access.includes.partials.header-buttons')
        
<div class="box box-warning">
    <div class="panel-body">

    {!! Form::open(['route' => 'admin.access.roles.permission-group.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) !!}

        <div class="form-group">
            {!! Form::label('name', trans('validation.attributes.permission_group_name'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.permission_group_name')]) !!}
            </div>
        </div><!--form control-->
        
        <div style="margin-bottom: 5px;" class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                <input type="submit" class="btn btn-primary" value="{{ trans('strings.save_button') }}" />
            </div>
        </div>

    {!! Form::close() !!}
    
    </div>
</div>
    
@stop