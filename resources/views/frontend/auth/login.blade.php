@extends('frontend.layouts.master')

@section('content')

<div style="min-height: 100vh" class="content-wrapper content-login">
    <div class="content new" style="display: inline-block;
    position: fixed;
    top: 20%;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;">
        <div class="main-tracking">
            <div class="head-section">
                <div class="left-section boxLogo">
                    <img src="{{ asset('public/img/barcode.png') }}" alt="Barcode Logo">
                </div>
                <div class="right-section boxTitle">
                    <h3 style="padding: 1%;">{!! trans('labels.login_box_title', ['app' => app_name()]) !!}</h3>
                    <span>{!! trans('labels.login_box_desc') !!}</span>
                </div>
            </div>

            <div class="content-section">
                @include('includes.partials.messages')
                {!! Form::open(['url' => 'auth/login', 'class' => 'form-horizontal', 'role' => 'form']) !!}
                <div class="form-start">
                    <div class="form-input" style="margin-top: 15px;">
                        {!! Form::label('email', trans('crud.users.email')) !!}<br>
                        {!! Form::input('email', 'email', old('email'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-input" style="margin-top: 15px;padding-bottom: 30px;">
                        {!! Form::label('password', trans('validation.attributes.password')) !!}<br>
                        {!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
                    </div>
                   

                    <div class="" style="padding-top: 30px;">
                        {!! Form::submit(trans('labels.login_button'), ['class' => 'btn btn-warning']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
