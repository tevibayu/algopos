<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

/**
 * Class RouteNeedsRole
 * @package App\Http\Middleware
 */
class RouteNeedsPermission {

	/**
	 * @param $request
	 * @param callable $next
	 * @param $permission
	 * @return mixed
     */
	public function handle($request, Closure $next, $permission)
	{
            if (! access()->can($permission)) {
                return Redirect::back()->withFlashDanger(trans('alerts.block'));
            }
            return $next($request);
	}
}