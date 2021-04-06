{!! Form::model($data, ['class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'frm_menu']) !!}
<div class="form-group">
    {!! Form::label('title', trans('crud.menu.title'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-8">
        <input type="hidden" id="menuid" name="menuid" value="{{ isset($data->id) ? $data->id : '' }}">
        <input type="hidden" id="type" name="type" value="{{ isset($type) ? $type : 'add' }}">
        {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => trans('crud.menu.title')]) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('link', trans('crud.menu.link'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-8">
        {!! Form::text('link', null, ['class' => 'form-control', 'placeholder' => trans('crud.menu.link')]) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('lang', trans('crud.menu.lang'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-8">
        {!! Form::text('lang', null, ['class' => 'form-control', 'placeholder' => trans('crud.menu.lang')]) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('icon', trans('crud.menu.icon'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-8">
        {!! Form::text('icon', null, ['class' => 'form-control', 'placeholder' => trans('crud.menu.icon')]) !!}
        <span>e.g. "fa fa-angle-right"</span>
    </div>
</div>
<div class="form-group">
    {!! Form::label('target', trans('crud.menu.target'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-6">
        {!! Form::select('target', ['sametab' => 'Same Tab', '_blank' => 'New Tab'], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('group_menu', trans('crud.menu.group'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-6">
        {!! Form::select('group_menu', $group_menus, null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('parent_id', trans('crud.menu.parent'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-8">
        {!! Form::select('parent_id', $parents, null, ['class' => 'form-control', 'onchange' => 'change_parent()']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('permission_id', trans('crud.menu.permission'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-8">
        {!! Form::select('permission_id', $permissions, null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group ">
    {!! Form::label('status', trans('crud.menu.status'), ['class' => 'control-label col-lg-2']) !!}
    <div class="col-sm-6">
        {!! Form::select('status', ['1' => trans('crud.menu.active'), '0' => trans('crud.menu.inactive')], null, ['class' => 'form-control']) !!}
        
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    <div class="col-sm-offset-2 col-sm-6">
        <input type="button" name="save" onclick="save_menu()" class="btn btn-primary" value="{{ trans('strings.save_button') }}" />
        <input type="button" name="btn_cancel" class="btn btn-danger" onclick="cancel()" value="{{ trans('strings.cancel_button') }}" />
    </div>
</div>
{!! Form::close() !!}
{!! HTML::script('public/js/backend/menu/form.js') !!}