@extends('backend.layouts.master')

@section('page-header')
    <h1>
        {{ app_name() }}
        <small>{{ trans('labels.change_photo_box_title') }}</small>
    </h1>
@endsection

@section('breadcrumbs')
    <li><a href="#"><i class="fa fa-laptop"></i> {{ trans('menus.title.settings') }}</a></li>
    <li class="active">{{ trans('labels.change_photo_box_title') }}</li>
@endsection

@section('content')

<div class="tab-content">
    <div class="box box-warning">
        <div class="panel-body">
            <div class="panel-body">
                {!! Form::open(['route' => 'photo.change', 'class' => 'form-horizontal', 'method' => 'POST', 'files' => true]) !!}
                
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            <?php
                                $my_photo_profile = access()->user()->photo != NULL && file_exists(base_path(access()->photo_profile_path() . access()->user()->photo)) ? access()->photo_profile_path() . access()->user()->photo : access()->user()->picture;
                            ?>
                            {!! HTML::image($my_photo_profile, 'photo profile', array('class' => 'img-thumbnail')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('photo', trans('crud.users.photo'), ['class' => 'col-md-2 control-label']) !!}
                        <div class="col-md-6">
                            <p class="form-control-static">{!! Form::file('photo') !!}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-2">
                            {!! Form::submit(trans('labels.save_button'), ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>

                {!! Form::close() !!}
            </div><!--panel body-->
        </div>
    </div>
</div>
@endsection