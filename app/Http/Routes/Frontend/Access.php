<?php

/**
 * Frontend Access Controllers
 */
$router->group(['namespace' => 'Auth'], function () use ($router)
{
	/**
	 * These routes require the user to be logged in
	 */
	$router->group(['middleware' => 'auth'], function () use ($router)
	{
		get('auth/logout', 'AuthController@getLogout');
        
        $router->group(['prefix' => 'admin'], function () use ($router)
        {
            $router->group(['middleware' => 'access.routeNeedsPermission:view-backend'], function () use ($router)
            {
                get('password/change', 'PasswordController@getChangePassword')->name('backend.password.change');
                post('password/change', 'PasswordController@postChangePassword')->name('password.change');
            });
        });
	});

	/**
	 * These reoutes require the user NOT be logged in
	 */
	$router->group(['middleware' => 'guest'], function () use ($router)
	{
		get('auth/login/{provider}', 'AuthController@loginThirdParty')->name('auth.provider');
		get('account/confirm/{token}', 'AuthController@confirmAccount')->name('account.confirm');
		get('account/confirm/resend/{user_id}', 'AuthController@resendConfirmationEmail')->name('account.confirm.resend');

		$router->controller('auth', 'AuthController');
		$router->controller('password', 'PasswordController');
	});
});