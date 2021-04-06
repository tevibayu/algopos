@extends ('backend.layouts.master')

@section ('title', trans('menus.language_settings'))

@section('page-header')
    <h1>
        {{ trans('menus.language_settings') }}
        <small>{{ $title }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> {{ trans('menus.dashboard') }}</a></li>
    <li>{!! link_to_route('admin.language.index', trans('menus.language_settings')) !!}</li>
    <li class="active">{!! $my_link !!}</li>
@stop

@section('content')

<div class="box box-warning">
    <div class="panel-body">
        {!! Form::open(['class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'language']) !!}
        
        @if($form_type == 'create')
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>{{ trans('crud.languages.type') }}</th>
                    <th>{{ trans('crud.languages.name') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{!! Form::select('type', $type, null, ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('crud.languages.name')]) !!}</td>
                </tr>
            </tbody>
        </table>
        <hr style="border-top: dotted 3px;" />
        @endif
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="25px">#</th>
                    <th>{{ trans('crud.languages.key') }}</th>
                    <th>{{ trans('menus.language-picker.langs.en') }}</th>
                    @if($form_type == 'edit')
                    <th>{{ Lang::has($group->lang) ? trans($group->lang) : $group->name }}</th>
                    @endif
                    <th width="30px"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td><a onclick="add_lang()" class="btn btn-xs btn-primary" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{ trans('crud.add_new_button') }}"><i class="fa fa-plus"></i></a></td>
                </tr>
            </tfoot>
            <tbody id="tbody">
                <?php $numb = 1; ?>
                @if(count($records))
                    @foreach($records as $key => $record)
                    <tr id="lang{{ $numb }}">
                        <td id="record_numb">{{ $numb }}</td>
                        <td><input type="text" class="form-control" name="key[]" value="{{ $key }}" /></td>
                        <td><input type="text" class="form-control" name="value_en[]" value="{{ $record }}" /></td>
                        @if($form_type == 'edit')
                        <?php
                            $my_value = '';
                            if ($type == 'core') {
                                $my_value = Lang::hasForLocale($filename.'.'.$key, $group->code) ? trans($filename.'.'.$key, array(), 'messages', $group->code) : '';
                            } else if ($type == 'module') {
                                $my_value = Lang::hasForLocale($module_name . '::' . $filename.'.'.$key, $group->code) ? trans($module_name . '::' . $filename.'.'.$key, array(), 'messages', $group->code) : '';
                            }
                        ?>
                        <td><input type="text" class="form-control" name="value_{{ $group->code }}[]" value="{{ $my_value }}" /></td>
                        @endif
                        <td><a style="cursor: pointer" class="btn btn-xs btn-danger" onclick="delete_lang({{ $numb }})" data-placement="top" data-toggle="tooltip" data-original-title="{{ trans('crud.delete_button') }}"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php $numb++ ?>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">{{ trans('strings.no_record') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="box-header" style="padding-left: 0px; padding-bottom: 0px;">
            <input type="submit" class="btn btn-primary" name="save" value="{!! trans('strings.save_button') !!}" />
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
    var numb = {{ $numb }};
    var alert_delete = '{{ trans("crud.alert_delete") }}';
    var code = '{{ $form_type == "edit" ? $group->code : "" }}';
    var form_type = '{{ $form_type }}';
</script>

{!! HTML::script('public/js/backend/languages/language_form.js') !!}

@stop