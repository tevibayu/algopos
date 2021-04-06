@extends('frontend.layouts.master')

@section('content')
	<div class="row">

		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('navs.dashboard') }}</div>

				<div class="panel-body">
					<div role="tabpanel">

                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{ trans('navs.my_information') }}</a></li>
                      </ul>

                      <div class="tab-content">

                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <table class="table table-striped table-hover table-bordered dashboard-table">
                                <tr>
                                    <th>{{ trans('validation.attributes.name') }}</th>
                                    <td>{!! $user->name !!}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('validation.attributes.email') }}</th>
                                    <td>{!! $user->email !!}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('validation.attributes.created_at') }}</th>
                                    <td>{!! date_format($user->created_at,"d M Y H:i:s") !!} ({!! $user->created_at->diffForHumans() !!})</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('validation.attributes.last_updated') }}</th>
                                    <td>{!! date_format($user->updated_at,"d M Y H:i:s") !!} ({!! $user->updated_at->diffForHumans() !!})</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('validation.attributes.actions') }}</th>
                                    <td>
                                        <a href="{!!route('frontend.profile.edit')!!}" class="btn btn-primary btn-xs">{{ trans('labels.edit_information') }}</a>
                                        <a href="{!!url('auth/password/change')!!}" class="btn btn-warning btn-xs">{{ trans('navs.change_password') }}</a>
                                        <a href="{!!url('auth/photo/change')!!}" class="btn btn-info btn-xs">{{ trans('navs.change_photo') }}</a>
                                    </td>
                                </tr>
                            </table>
                        </div><!--tab panel profile-->

                      </div><!--tab content-->

                    </div><!--tab panel-->

				</div><!--panel body-->

			</div><!-- panel -->

		</div><!-- col-md-10 -->

	</div><!-- row -->
@endsection