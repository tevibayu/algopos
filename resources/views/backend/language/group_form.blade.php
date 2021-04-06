<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">{{ $title }}</h4>
</div>
@if($form_type == 'create')
{!! Form::open(['route' => 'admin.language.create_group', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-group', 'files' => true]) !!}
@elseif($form_type == 'edit')
{!! Form::model($record, ['route' => ['admin.language.edit_group'], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'edit-group', 'files' => true]) !!}
@endif
    <div class="modal-body">
        <div class="form-group">
            {!! Form::label('code', trans('crud.languages.code'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-4">
                @if($form_type == 'create')
                    {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => trans('crud.languages.code')]) !!}
                @elseif($form_type == 'edit')
                    {!! Form::hidden('id', $record->id_language) !!}
                    {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => trans('crud.languages.code'), 'disabled' => '']) !!}
                @endif
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('lang', trans('crud.languages.lang'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-9">
                {!! Form::text('lang', null, ['class' => 'form-control', 'placeholder' => trans('crud.languages.lang')]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name', trans('crud.languages.name'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-6">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('crud.languages.name')]) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('flag', trans('crud.languages.flag'), ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-9">
                {!! Form::file('flag') !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="{{ trans('strings.save_button') }}" />
    </div>
{!! Form::close() !!}

@if($form_type == 'create')
{!! HTML::script('public/js/backend/languages/create_group.js') !!}
@elseif($form_type == 'edit')
<script>
    var type = "{{ $type }}";
    var id_language = {{ $record->id_language }};
</script>
{!! HTML::script('public/js/backend/languages/edit_group.js') !!}
@endif