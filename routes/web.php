<?php


Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

/* ROUTE DELIVERYS! */
Route::get('/delivery', 'DeliveryRouteHomeController@index')->name('delivery.home');
Route::post('/delivery/get_route', 'DeliveryRouteHomeController@get_route')->name('delivery.get_route');
Route::post('/delivery/save_order', 'DeliveryRouteHomeController@save_ship_order')->name('delivery.save_order');
Route::post('/delivery/update_order', 'DeliveryRouteHomeController@update_ship_order')->name('delivery.update_order');
Route::post('/delivery/delete_order', 'DeliveryRouteHomeController@delete_ship_order')->name('delivery.delete_order');

Route::get('/delivery/orders/{id_zone?}', 'DeliveryOrdersController@list_orders')->name('delivery.list_orders');
Route::post('/delivery/orders/set_zone', 'DeliveryRouteHomeController@set_zone_order')->name('delivery.set_zone_order');
Route::post('/delivery/orders/set_status', 'DeliveryOrdersController@set_status')->name('delivery.order.set_status');
Route::post('/delivery/orders/update_address', 'DeliveryRouteHomeController@update_address')->name('delivery.order.update_address');
Route::post('/delivery/orders/complete_order', 'DeliveryOrdersController@complete_order')->name('delivery.order.complete_order');

Route::get('/delivery/zones', 'DeliveryZonesController@index')->name('delivery.zones');
Route::post('/delivery/zones/add', 'DeliveryZonesController@add_zone')->name('delivery.add_zone');
Route::post('/delivery/zones/set_neighborhood', 'DeliveryZonesController@set_neighborhood')->name('delivery.set_neighborhood');

Route::post('/delivery/create_route/', 'DeliveryCreateRouteController@create_route')->name('delivery.create_route');
Route::get('/delivery/routes/{id_zone}', 'DeliveryRoutesController@index')->name('delivery.routes');

/* GORA ADMIN 
Route::get('/admin', 'GoraAdminController@index')->name('admin.home');
Route::get('/admin/add_order', 'GoraAdminController@add_order')->name('admin.add_order');
Route::post('/admin/add_order', 'GoraAdminController@add_order')->name('admin.add_order');
Route::post('/admin/add_order_company', 'GoraAdminController@add_order_company')->name('admin.add_order_company');
*/
/*  USERS 

Route::get('/order/{id}', 'HomeController@view_order')
    ->where('id', '[0-9]+')
    ->name('view_order');

Route::post('/add_order', 'HomeController@add_order')->name('add_order');

Route::resource('/menus', 'MenusController');
Route::get('/getEmpleados/{id}', 'MainController@get_empleados')
    ->where('id', '[0-9]+')
    ->name('get_empleados');
 */
/* COMPANY ADMIN 

Route::get('/company', 'CompanyAdminController@index')->name('company.home');
Route::get('/company/add_user', 'CompanyAdminController@add_user')->name('company.add_user');
Route::post('/company/add_user', 'CompanyAdminController@add_user')->name('company.add_user');
Route::get('/company/show_users/{id?}', 'CompanyAdminController@show_users')->name('company.show_users');
*/