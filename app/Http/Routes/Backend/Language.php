<?php

$router->group([
    'namespace' => 'Language',
    'middleware' => 'access.routeNeedsPermission:view-language'
], function() use ($router)
{
    get('language', 'LanguageController@index')->name('admin.language.index');
    post('language/create_group', 'LanguageController@create_group')->name('admin.language.create_group');
    post('language/edit_group', 'LanguageController@edit_group')->name('admin.language.edit_group');
    post('language/delete_group', 'LanguageController@delete_group')->name('admin.language.delete_group');
    
    get('language/create_language', 'LanguageController@create_language')->name('admin.language.create_language');
    post('language/create_language', 'LanguageController@create_language')->name('admin.language.create_language');
    get('language/edit_language/{type}/{name}/{code}', 'LanguageController@edit_language')->name('admin.language.edit_language');
    post('language/edit_language/{type}/{name}/{code}', 'LanguageController@edit_language')->name('admin.language.edit_language');
    get('language/delete_language/{type}/{name}', 'LanguageController@delete_language')->name('admin.language.delete_language');
});