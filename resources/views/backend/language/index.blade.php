@extends ('backend.layouts.master')

@section ('title', trans('menus.language_settings'))

@section('page-header')
    <h1>
        {{ trans('menus.language_settings') }}
        <small>{{ trans('menus.language_settings') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li class="active">{!! link_to_route('admin.language.index', trans('menus.language_settings')) !!}</li>
@stop

@section('content')

<div class="box box-warning">
    <div class="panel-body">
        <div>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#languages" aria-controls="languages" role="tab" data-toggle="tab">Languages</a></li>
                <li role="presentation"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">Groups</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="languages" style="margin-bottom: 5px;">
                    <div class="box-header with-border">
                        @permission($createPermission)
                        <div class="col-sm-9" style="padding: 5px 0px 0px 0px;">
                            <a href="{{ url('admin/language/create_language') }}" class="btn btn-primary">{{ trans('crud.create_button') }}</a>
                        </div>
                        @endauth
                    </div>
                    <table class="table table-striped table-bordered table-hover table-languages">
                        <thead>
                            <tr>
                                <th width="25px">#</th>
                                <th>{{ trans('crud.languages.type') }}</th>
                                <th>{{ trans('crud.languages.lang_name') }}</th>
                                <th class="column-manage">{{ trans('crud.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="lang_tbody">
                            @if(count($languages))
                                <?php $lang_numb = 1; ?>
                                @foreach($languages as $language)
                                <tr>
                                    <td id="language_numb">{{ $lang_numb }}</td>
                                    <td>{{ ucfirst($language['type']) }}{{ $language['module_name'] != null ? ' ' . ucfirst($language['module_name']) : '' }}</td>
                                    <td>{{ ucfirst($language['lang_name']) }}</td>
                                    <td>
                                        @permission($editPermission)
                                            @if(count($groups->toArray()))
                                                @foreach($groups as $group)
                                                    @if($group->code != 'en')
                                                        <?php
                                                            $group_name = Lang::has($group->lang) ? trans($group->lang) : $group->name;
                                                        ?>
                                                        <a href="{{ url('admin/language/edit_language/' . strtolower($language['type']) . strtolower($language['module_name']) . '/' . strtolower($language['lang_name']) . '/' . $group->code) }}" class="btn btn-xs btn-primary" data-placement="top" data-toggle="tooltip" data-original-title="{{ $group_name }}">{{ $group->code }} <i class="fa fa-pencil"></i></a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endauth
                                        @permission($deletePermission)
                                        <a href="{{ url('admin/language/delete_language/' . strtolower($language['type']) . strtolower($language['module_name']) . '/' . strtolower($language['lang_name'])) }}" onclick="return confirm('{{ trans("crud.alert_delete") }}');" style="cursor: pointer" class="btn btn-xs btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('crud.delete_button') }}"><i class="fa fa-trash"></i></a>
                                        @endauth
                                    </td>
                                </tr>
                                <?php $lang_numb++; ?>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="groups">
                    <div class="box-header with-border">
                        @permission($createPermission)
                        <div class="col-sm-9" style="padding: 5px 0px 5px 0px;">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" onclick="create_group()">{{ trans('crud.create_button') }}</button>
                        </div>
                        @endauth
                    </div>
                    <table class="table table-striped table-bordered table-hover table-groups">
                        <thead>
                            <tr>
                                <th width="25px">#</th>
                                <th>{{ trans('crud.languages.code') }}</th>
                                <th>{{ trans('crud.languages.lang') }}</th>
                                <th>{{ trans('crud.languages.name') }}</th>
                                <th>{{ trans('crud.languages.flag') }}</th>
                                <th width="50px">{{ trans('crud.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="groups_tbody">
                            @if(count($groups->toArray()))
                                <?php $group_numb = 1; ?>
                                @foreach($groups as $group)
                                <tr id="group{{ $group->id_language }}">
                                    <td id="group_numb">{{ $group_numb }}</td>
                                    <td id="record_code{{ $group->id_language }}">{{ $group->code }}</td>
                                    <td id="record_lang{{ $group->id_language }}">{{ $group->lang }}</td>
                                    <td id="record_name{{ $group->id_language }}">{{ $group->name }}</td>
                                    <td id="record_flag{{ $group->id_language }}">
                                        @if($group->flag != '')
                                            @if($group->code == 'en')
                                                @if(file_exists(base_path($group->flag)))
                                                    <img src="{{ asset('public/img/english.png') }}" style="width: 20px; height: 20px;">
                                                @endif
                                            @else
                                                @if(file_exists(base_path(access()->language_path() . $group->flag)))
                                                    <img src="{{ asset(access()->language_path() . $group->flag) }}" style="width: 20px; height: 20px;">
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($group->code != 'en')
                                            @permission($editPermission)
                                            <a style="cursor: pointer" class="btn btn-xs btn-primary" onclick="edit_group({{ $group->id_language }})" data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('crud.edit_button') }}"><i class="fa fa-pencil"></i></a>
                                            @endauth
                                            @permission($deletePermission)
                                            <a style="cursor: pointer" class="btn btn-xs btn-danger" onclick="delete_group({{ $group->id_language }})" data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('crud.delete_button') }}"><i class="fa fa-trash"></i></a>
                                            @endauth
                                        @endif
                                    </td>
                                </tr>
                                <?php $group_numb++; ?>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    var group_numb = {{ $group_numb }};
    var record_numb = 1;
    var alert_delete = '{{ trans("crud.alert_delete") }}';
    
    var url_create_group = '{{ url("admin/language/create_group") }}';
    var url_edit_group = '{{ url("admin/language/edit_group") }}';
    var url_delete_group = '{{ url("admin/language/delete_group") }}';
    var count_groups = {{ count($groups->toArray()) }};
    
    var url_create_lang = '{{ url("admin/language/create_language") }}';
    var count_languages = {{ count($languages) }};
</script>

{!! HTML::script('public/js/backend/languages/languages.js') !!}

@stop