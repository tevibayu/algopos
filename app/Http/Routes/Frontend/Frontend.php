<?php

/**
 * Frontend Controllers
 */
get('/', 'FrontendController@index')->name('home');

/**
 * These frontend controllers require the user to be logged in
 */
$router->group(['middleware' => 'auth'], function ()
{
	get('dashboard', 'DashboardController@index')->name('frontend.dashboard');
	// get('profile/edit', 'ProfileController@edit')->name('frontend.profile.edit');
	// patch('profile/update', 'ProfileController@update')->name('frontend.profile.update');
	// get('auth/photo/change', 'ProfileController@photo');
	// post('auth/photo/change', 'ProfileController@photo')->name('photo.change');
});

$router->group(['middleware' => 'auth'], function () use ($router)
{
	get('dashboard', 'DashboardController@index')->name('frontend.dashboard');
    
    $router->group(['prefix' => 'admin'], function () use ($router)
	{
		$router->group(['middleware' => 'access.routeNeedsPermission:view-backend'], function () use ($router)
		{
			get('profile/edit', 'ProfileController@edit')->name('frontend.profile.edit');
            patch('profile/update', 'ProfileController@update')->name('frontend.profile.update');
            get('photo/change', 'ProfileController@photo');
            post('photo/change', 'ProfileController@photo')->name('photo.change');
		});
	});
});