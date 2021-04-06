<?php

$router->group([
    'namespace' => 'Settings',
    'middleware' => 'access.routeNeedsPermission:view-settings'
], function() use ($router)
{
    get('settings/general', 'SettingsController@index')->name('admin.settings.general');
    post('settings/save_general_settings', 'SettingsController@save_general_settings')->name('admin.settings.save_general_settings');
    get('settings/email', 'SettingsController@email')->name('admin.settings.email');
    post('settings/save_email_settings', 'SettingsController@save_email_settings')->name('admin.settings.save_email_settings');
});