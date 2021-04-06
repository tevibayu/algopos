<?php

$router->group([
    'namespace' => 'Menu',
    'middleware' => 'access.routeNeedsPermission:view-menu'
], function() use ($router)
{
    get('menu', 'MenuController@index')->name('admin.menu.index');
    get('menu/create', 'MenuController@create')->name('admin.menu.create');
    get('menu/edit/{id}', 'MenuController@edit')->name('admin.menu.edit');
    post('menu/save_order', 'MenuController@save_order')->name('admin.menu.save_order');
    post('menu/save_menu', 'MenuController@save_menu')->name('admin.menu.save_menu');
    post('menu/delete', 'MenuController@delete')->name('admin.menu.delete');
});