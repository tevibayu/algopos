@extends('backend.layouts.master')

@section('page-header')
<h1>
    {{ app_name() }}
    <small>{{ trans('labels.update_information_box_title') }}</small>
</h1>
@endsection

@section('breadcrumbs')
<li><a href="#"><i class="fa fa-laptop"></i> {{ trans('menus.title.settings') }}</a></li>
<li class="active">{{ trans('labels.update_information_box_title') }}</li>
@endsection

@section('content')
<div class="tab-content">
    <div class="box box-warning">
        <div class="panel-body">
            {!! Form::model($user, ['route' => 'frontend.profile.update', 'class' => 'form-horizontal', 'method' => 'PATCH']) !!}

            <div class="form-group">
                {!! Form::label('name', trans('validation.attributes.name'), ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            @if ($user->canChangeEmail())
            <div class="form-group">
                {!! Form::label('email', trans('validation.attributes.email'), ['class' => 'col-md-2 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::input('email', 'email', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            @endif
            
            

            <div class="form-group">
                <div class="col-md-6 col-md-offset-2">
                    {!! Form::submit(trans('labels.save_button'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}

        </div><!--panel body-->
    </div>
</div>
@endsection