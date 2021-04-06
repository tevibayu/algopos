


                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
								<table class="table table-striped table-default table-hover" style="margin-bottom: 0px;" id="filltable">
									<thead>
										<tr>
											
											<th width="25px">#</th>
											<th>{{ trans('transaction::lang.crud.product_name') }}</th>
											<th>{{ trans('transaction::lang.crud.qty') }}</th>
											<th>{{ trans('transaction::lang.crud.price') }}</th>
											<th>{{ trans('transaction::lang.crud.total_price') }}</th>
											<th>{{ trans('transaction::lang.crud.order_date') }}</th>
										</tr>
									</thead>
									<tbody>
										@if($records->count())
										@foreach ($records as $key => $record)
										<tr>
								
											<td>{{ $key+1 }}</td>
											<td>{!! $record->product_name !!}</td>
											<td>{!! $record->qty !!}</td>
											<td>{!! number_format($record->price, 2) !!}</td>
											<td>{!! number_format($record->total_price, 2) !!}</td>
											<td>{!! date("d, M Y H:i", strtotime($record->created_at)) !!}</td>
											
											
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

                 
                
