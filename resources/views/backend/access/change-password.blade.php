@extends ('backend.layouts.master')

@section ('title', 'User Management | Change User Password')

@section ('before-styles-end')
    {!! HTML::style('public/css/plugin/jquery.onoff.css') !!}
@stop

@section('page-header')
    <h1>
        User Management
        <small>Change Password</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li>{!! link_to_route('admin.access.users.index', 'User Management') !!}</li>
    <li>{!! link_to_route('admin.access.users.edit', "Edit ".$user->name, $user->id) !!}</li>
    <li class="active">{!! link_to_route('admin.access.user.change-password', 'Change Password', $user->id) !!}</li>
@stop

@section('content')
    @include('backend.access.includes.partials.header-buttons')
<div class="box box-warning">
    <div class="panel-body">
    {!! Form::open(['route' => ['admin.access.user.change-password', $user->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) !!}

        <div class="form-group">
            <label class="col-lg-2 control-label">Password</label>
            <div class="col-lg-10">
                {!! Form::password('password', ['class' => 'form-control']) !!}
            </div>
        </div><!--form control-->

        <div class="form-group">
            <label class="col-lg-2 control-label">Confirm Password</label>
            <div class="col-lg-10">
                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
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