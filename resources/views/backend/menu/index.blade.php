@extends ('backend.layouts.master')

@section ('title', trans('menus.menu'))

@section('page-header')
    <h1>
        {{ trans('menus.menu') }}
        <small>{{ trans('menus.view_menu') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li class="active">{!! link_to_route('admin.menu.index', trans('menus.menu')) !!}</li>
@stop

@section('content')
{!! HTML::style('public/css/backend/plugin/nestable/jquery.nestable.css') !!}
{!! HTML::script('public/js/backend/plugin/nestable/jquery.nestable.js') !!}
{!! HTML::script('public/js/backend/menu/menu.js') !!}

<div class="box box-warning">
    <div class="row">
    	<div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    {!! Form::open(['route' => 'admin.menu.index', 'role' => 'form', 'method' => 'post', 'id' => 'frm_menu_order']) !!}
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ trans('menus.menu_item_order') }}</div>
                            <div class="panel-body" id="mn_item">
                                <div id="nestable" class="dd">
                                    <ol class="dd-list">
                                        @foreach($menus as $key => $mn)
                                        <li data-id="{{ $mn->id }}" class="dd-item">
                                            <span class="pull-right" style="margin-right: 5px; margin-top: 5px;">
                                            @permission('edit-menu')
                                            <span style="cursor: pointer;" class="fa fa-pencil" onclick="editmenu({{ $mn->id }})"></span>
                                            @endauth
                                            @permission('delete-menu')
                                            <span style="cursor: pointer;" class="fa fa-trash" onclick="delete_menu({{ $mn->id }})"></span>
                                            @endauth
                                            </span>
                                            <div class="dd-handle">
                                                {{ $mn->title }}
                                            </div>
                                            @if(count($mn->child))
                                            <ol class="dd-list">
                                                @foreach($mn->child as $key => $submenu)
                                                <li data-id="{{ $submenu->id }}" class="dd-item">
                                                    <span class="pull-right" style="margin-right: 5px; margin-top: 5px;">
                                                    @permission('edit-menu')
                                                    <span style="cursor: pointer;" class="fa fa-pencil" title="Edit" onclick="editmenu({{ $submenu->id }})"></span>
                                                    @endauth
                                                    @permission('delete-menu')
                                                    <span style="cursor: pointer;" class="fa fa-trash" onclick="delete_menu({{ $submenu->id }})"></span>
                                                    @endauth
                                                    </span>
                                                    <div class="dd-handle">
                                                        {{ $submenu->title }}
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ol>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ol>
                                </div>
                                <div class="col-md-12">
                                    @permission('create-menu')
                                    <input type="button" id="save_order" name="save_order" class="btn btn-primary" onclick="save_order_menu()" style="margin-top: 10px;" value="{{ trans('strings.save_button') }}" />
                                    @endauth
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-8">
                    @permission('create-menu')
                        <div class="panel panel-default">
                            <div class="panel-heading" id="frm_menu_head">{{ trans('menus.create_menu') }}</div>
                            <div class="panel-body">
                                <div id="form-area"></div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var create_menu = '{{ trans("menus.create_menu") }}';
    var edit_menu = '{{ trans("menus.edit_menu") }}';
    var alert_delete = '{{ trans("crud.alert_delete") }}';
</script>

@stop