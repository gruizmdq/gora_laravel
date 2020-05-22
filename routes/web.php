<?php

Auth::routes();

/* ROUTE DELIVERYS! */
Route::get('/delivery', 'DeliveryRouteController@index')->name('delivery.home');
Route::post('/delivery/get_route', 'DeliveryRouteController@get_route')->name('delivery.get_route');

/* GORA ADMIN */
Route::get('/admin', 'GoraAdminController@index')->name('admin.home');
Route::get('/admin/add_order', 'GoraAdminController@add_order')->name('admin.add_order');
Route::post('/admin/add_order', 'GoraAdminController@add_order')->name('admin.add_order');
Route::post('/admin/add_order_company', 'GoraAdminController@add_order_company')->name('admin.add_order_company');

/*  USERS  */
Route::get('/', 'HomeController@index')->name('home');

Route::get('/order/{id}', 'HomeController@view_order')
    ->where('id', '[0-9]+')
    ->name('view_order');

Route::post('/add_order', 'HomeController@add_order')->name('add_order');

Route::resource('/menus', 'MenusController');
Route::get('/getEmpleados/{id}', 'MainController@get_empleados')
    ->where('id', '[0-9]+')
    ->name('get_empleados');

/* COMPANY ADMIN */

Route::get('/company', 'CompanyAdminController@index')->name('company.home');
Route::get('/company/add_user', 'CompanyAdminController@add_user')->name('company.add_user');
Route::post('/company/add_user', 'CompanyAdminController@add_user')->name('company.add_user');
Route::get('/company/show_users/{id?}', 'CompanyAdminController@show_users')->name('company.show_users');
