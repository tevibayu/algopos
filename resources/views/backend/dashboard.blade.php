@extends('backend.layouts.master')

@section('page-header')
<h1>
	&nbsp;
</h1>
@endsection

@section('breadcrumbs')
<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-pie-chart"></i> {{ trans('menus.dashboard') }}</a></li>
@endsection

@section('content')

<div class="row">
	<div class="col-md-8">

		<div class="box box-solid">

			<div class="box-body border-radius-none chart-responsive">
				{!! $chart->render() !!}
			</div>

		</div>

	</div>
	<div class="col-md-4">

		<div class="box box-solid">

			<div class="box-body border-radius-none chart-responsive">
				{!! $pie->render() !!}
			</div>

		</div>

	</div>
</div>



<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<p class="text-center">
							<strong>Sales: 1 April, 2021 - 6 April, 2021</strong>
						</p>

						<table class="table table-striped table-default table-hover" style="margin-bottom: 0px;" id="filltable">
							<thead>
								<tr>

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
								@if($last->count())
								@foreach ($last as $key => $record)
								<tr>

									<?php $order = access()->generateInvoiceNo($record->id_order, $record->created_at); ?>
									<td>{{ $key+1 }}</td>
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
				</div>

			</div>
		</div>
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

<script>
	var url_form_detail = '{{ url("admin/transaction/detail")  }}';
</script>

{!! HTML::script('public/js/Chart.js') !!}
{!! HTML::script('public/js/dashboard.js') !!}

@endsection