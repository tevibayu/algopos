<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()
{
    Route::group(['middleware' => 'access.routeNeedsPermission:view-backend'], function()
    {
        Route::group(['middleware' => 'access.routeNeedsPermission:view-localizations'], function()
        {
            Route::group(['namespace' => 'Modules\Localizations\Http\Controllers'], function()
            {
                Route::group(['middleware' => 'access.routeNeedsPermission:view-except-localizations'], function()
                {
                    get('localizations/except', 'LocalizationsController@except')->name('admin.localizations.except');
                    get('localizations/except/create', 'LocalizationsController@create_except')->name('admin.localizations.create_except');
                    post('localizations/except/create', 'LocalizationsController@create_except')->name('admin.localizations.create_except');
                    get('localizations/except/edit/{id}', 'LocalizationsController@edit_except')->name('admin.localizations.edit_except');
                    post('localizations/except/edit/{id}', 'LocalizationsController@edit_except')->name('admin.localizations.edit_except');
                    get('localizations/except/delete/{id}', 'LocalizationsController@delete_except')->name('admin.localizations.delete_except');
                    post('localizations/except/batch_delete', 'LocalizationsController@batch_delete_except')->name('admin.localizations.batch_delete_except');
                    post('localizations/except/get_records', 'LocalizationsController@get_records')->name('admin.localizations.get_records');
                });
                
                Route::group(['middleware' => 'access.routeNeedsPermission:view-popular-localizations'], function()
                {
                    get('localizations/popular', 'LocalizationsController@popular')->name('admin.localizations.popular');
                    get('localizations/popular/create', 'LocalizationsController@create_popular')->name('admin.localizations.create_popular');
                    post('localizations/popular/create', 'LocalizationsController@create_popular')->name('admin.localizations.create_popular');
                    get('localizations/popular/edit/{id}', 'LocalizationsController@edit_popular')->name('admin.localizations.edit_popular');
                    post('localizations/popular/edit/{id}', 'LocalizationsController@edit_popular')->name('admin.localizations.edit_popular');
                    get('localizations/popular/delete/{id}', 'LocalizationsController@delete_popular')->name('admin.localizations.delete_popular');
                    post('localizations/popular/batch_delete', 'LocalizationsController@batch_delete_popular')->name('admin.localizations.batch_delete_popular');
                    post('localizations/popular/get_records', 'LocalizationsController@get_records')->name('admin.localizations.get_records');
                });
                
                Route::group(['middleware' => 'access.routeNeedsPermission:view-feature-localizations'], function()
                {
                    get('localizations/feature', 'LocalizationsController@feature')->name('admin.localizations.feature');
                    get('localizations/feature/create', 'LocalizationsController@create_feature')->name('admin.localizations.create_feature');
                    post('localizations/feature/create', 'LocalizationsController@create_feature')->name('admin.localizations.create_feature');
                    get('localizations/feature/edit/{id}', 'LocalizationsController@edit_feature')->name('admin.localizations.edit_feature');
                    post('localizations/feature/edit/{id}', 'LocalizationsController@edit_feature')->name('admin.localizations.edit_feature');
                    get('localizations/feature/delete/{id}', 'LocalizationsController@delete_feature')->name('admin.localizations.delete_feature');
                    post('localizations/feature/batch_delete', 'LocalizationsController@batch_delete_feature')->name('admin.localizations.batch_delete_feature');
                    post('localizations/feature/get_records', 'LocalizationsController@get_records')->name('admin.localizations.get_records');
                });
            });
        });
    });
});