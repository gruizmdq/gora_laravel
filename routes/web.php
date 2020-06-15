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


/* ROUTE SELLERS! */
Route::get('/seller', 'SellerHomeController@index')->name('seller.home');
Route::get('/seller/orders/{offset?}', 'SellerOrdersController@list_orders')->name('seller.orders');


/* ROUTE ADMIN! */
Route::get('/admin/{date?}', 'AdminHomeController@index')->name('admin.home');
Route::post('/admin/delete_order', 'AdminHomeController@delete_order')->name('admin.delete_order');
