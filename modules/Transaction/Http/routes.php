<?php

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()
{
    Route::group(['middleware' => 'access.routeNeedsPermission:view-backend'], function()
    {
        Route::group(['namespace' => 'Modules\Transaction\Http\Controllers', 'middleware' => 'access.routeNeedsPermission:view-master'], function()
        {
            get('transaction', 'TransactionController@index')->name('admin.transaction.index');
            post('transaction/detail', 'TransactionController@detail')->name('admin.transaction.detail');
        });
    });
});