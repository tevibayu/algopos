<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

/**
 * Class RouteNeedsRole
 * @package App\Http\Middleware
 */
class RouteNeedsRole {

	/**
	 * @param $request
	 * @param callable $next
	 * @param $role
	 * @return mixed
     */
	public function handle($request, Closure $next, $role)
	{
            if (! access()->hasRole($role)) {
                return Redirect::back()->withFlashDanger(trans('alerts.block'));
            }
            return $next($request);
	}
}
