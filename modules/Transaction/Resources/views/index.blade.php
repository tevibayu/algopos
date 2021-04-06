@extends ('backend.layouts.master')

@section ('title', trans('transaction::lang.menus.index'))

@section('page-header')
    <h1>
        {{ trans('transaction::lang.menus.index') }}
        <small>{{ trans('transaction::lang.menus.view') }}</small>
    </h1>
@endsection

@section ('breadcrumbs')
    <li><a href="#"><i class="fa fa-folder-open"></i> {{ trans('menus.title.master_data') }}</a></li>
    <li class="active">{!! link_to_route('admin.transaction.index', trans('transaction::lang.menus.index')) !!}</li>
@stop

@section('content')

<div class="box box-warning">
	<div class="box-header">
		<div class="col-sm-9" style="padding: 5px 0px 5px 0px;">
			
		</div>
		<div class="col-sm-3" style="padding: 5px 0px 5px 0px;">
			{!! Form::open(['route' => 'admin.transaction.index', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'get', 'id' => 'search']) !!}
			<div class="input-group">
				<input type="text" autocomplete="off" autofocus="" placeholder="{{ trans('strings.search') }}" class="form-control pull-right" value="{{ $search }}" name="search">
				<div class="input-group-btn">
					<button class="btn btn-s btn-primary"><i class="fa fa-search"></i></button>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
	<div class="panel-body">
		
		<div class="table-responsive">
			<table class="table table-striped table-default table-hover" style="margin-bottom: 0px;" id="filltable">
				<thead>
					<tr>
						<th width="30px" class="column-check">
							<div class="checkbox">
								<input id="check_all" type="checkbox" />
								<label for="check_all"></label>
							</div>
						</th>
						<th width="25px">#</th>
						<th>{{ trans('transaction::lang.crud.id') }}</th>
						<th>{{ trans('transaction::lang.crud.buyer_name') }}</th>
						<th>{{ trans('transaction::lang.crud.address') }}</th>
						<th>{{ trans('transaction::lang.crud.total_amount') }}</th>
						<th>{{ trans('transaction::lang.crud.order_date') }}</th>

						<th width="60px" class="column-manage">{{ trans('crud.actions') }}</th>
					</tr>
				</thead>
				<tbody>
					@if($records->count())
					@foreach ($records as $record)
					<tr>
						<td>
							<div class="checkbox">
								
							</div>
						</td>
						<?php $order = access()->generateInvoiceNo($record->id_order, $record->created_at); ?>
						<td></td>
						<td>{!! $order !!}</td>
						<td>{!! $record->buyer_name !!}</td>
						<td>{!! $record->address !!}</td>
						<td>{!! number_format($record->total_amount, 2) !!}</td>
						<td>{!! date("d, M Y H:i", strtotime($record->created_at)) !!}</td>
						
						<td class="boxManage">
							<a class="btn btn-xs btn-primary" href="javascript:void(0)" onclick="detail('{{ $record->id_order }}', '{{ $order }}')"><i title="" class="fa fa-eye"></i></a>
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="7" style="text-align:center;padding: 20px"><b>{{ trans('strings.no_record') }}</b></td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		@if($records->count())
		<div class="box-header">
			
			<div class="pull-right">
				{!! $records->appends(['search' => $search])->render() !!}
			</div>
		</div>
		@endif



	</div>
</div>


<div class="modal fade in" data-refresh="true" id="seeDetail" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">

			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Order Detail # -</h4>
			</div>

			<div class="row">
				<div class="col-md-12">
				<div class="modal-body">
				</div>
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-close-primary" data-dismiss="modal">Close</button>
			</div>
		</div>

	</div>
</div>

@if($records->count())
    <script>
        var record_numb = {!! $numb !!};

        function detail(id, orderid)
	    {
	        var url_form_detail = '{{ url("admin/transaction/detail")  }}';
	        $('#seeDetail .modal-title').text('Order Detail : #' + orderid);

	        $.ajax({
	            type: "POST",
	            url: url_form_detail,
	            data: {id : id},
	            // dataType: 'json',
	            beforeSend: function() {
	                $('.ajax_loader').show();
	            },
	            success: function(result){
	                $('#seeDetail .modal-body').html(result);
	                $('#seeDetail').modal('show');
	                $('.ajax_loader').hide();
	            }
	        });
	    }
    </script>

    {!! HTML::script('public/js/backend/plugin/datatable/datatable_init.js'.'?v='.env("APP_VERSION", "1.0.0")) !!}
@endif

@endsection