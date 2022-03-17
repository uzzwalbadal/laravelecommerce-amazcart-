<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::middleware(['auth','seller'])->prefix('shipping')->group(function() {
    Route::get('/rate', 'ShippingController@index')->name('shipping_methods.index')->middleware(['permission']);
    Route::get('/list', 'ShippingController@create')->name('shipping_methods.create');
    Route::post('/store', 'ShippingController@store')->name('shipping_methods.store')->middleware(['permission','prohibited_demo_mode']);
    Route::get('/rate/edit/{id}', 'ShippingController@edit')->name('shipping_methods.edit');
    Route::post('/update', 'ShippingController@update')->name('shipping_methods.update')->middleware(['permission','prohibited_demo_mode']);
    Route::post('/update-status', 'ShippingController@update_status')->name('shipping_methods.update_status')->middleware(['permission','prohibited_demo_mode']);
    Route::post('/update-approve-status', 'ShippingController@update_approve_status')->name('shipping_methods.update_approve_status')->middleware(['permission','prohibited_demo_mode']);
    Route::post('/delete', 'ShippingController@destroy')->name('shipping_methods.destroy')->middleware(['permission','prohibited_demo_mode']);
});

Route::middleware(['auth','seller'])->prefix('shipping')->as('shipping.')->group(function() {

    //configuration
    Route::get('/configuration', 'ShippingConfigurationController@index')->name('configuration.index');
    Route::post('/configuration-update', 'ShippingConfigurationController@update')->name('configuration.update');

    //label_terms_conditions
    Route::get('label/terms-conditions', 'LabelConfigController@index')->name('label.terms_condition.index');
    Route::get('label/terms-conditions/delete/{id}', 'LabelConfigController@conditionDelete')->name('label.terms_condition.destroy');
    Route::post('label/terms-conditions/update', 'LabelConfigController@update')->name('label.terms_condition.update');

    //shipping orders
    Route::get('/shipping-orders', 'ShippingOrderController@index')->name('pending_orders.index');
    Route::get('/method-change/{id}', 'ShippingOrderController@singleOrderMethodChange')->name('single_order_method_change');
    Route::post('/method-update', 'ShippingOrderController@methodUpdate')->name('method_update');
    Route::get('/label-generate/{id}', 'ShippingOrderController@labelGenerate')->name('label_generate');
    Route::get('/invoice-generate/{id}', 'ShippingOrderController@invoiceGenerate')->name('invoice_generate');
    Route::get('/update-carrier-order/{id}', 'ShippingOrderController@updateCarrierOrder')->name('update_carrier_order');
    Route::get('/edit-carrier-order/{id}', 'ShippingOrderController@editCarrierOrder')->name('edit_carrier_order');
    Route::post('/carrier-order-update', 'ShippingOrderController@carrierOrderUpdate')->name('carrier_order_update');
    Route::get('/customer-address-edit/{id}', 'ShippingOrderController@customerAddressEdit')->name('customer_address_edit');
    Route::post('/customer-address-update', 'ShippingOrderController@customerAddressUpdate')->name('customer_address_update');
    Route::get('/carrier-status/{id}', 'ShippingOrderController@carrierStatus')->name('carrier_status');

    //packaging
    Route::get('/packaging/edit/{id}', 'ShippingOrderController@editPackaging')->name('packaging.edit');
    Route::post('/packaging/update', 'ShippingOrderController@updatePackaging')->name('packaging.update');

    //shipping-carrier-change

    Route::post('/carrier-change', 'ShippingOrderController@carrierChange')->name('carrier_change');

    //pickup_locations
    Route::resource('pickup_locations', 'PickupLocationController')->except(['create','destroy']);
    Route::post('pickup_location/delete', 'PickupLocationController@destroy')->name('pickup_locations.destroy');
    Route::post('pickup_location/status', 'PickupLocationController@status')->name('pickup_locations.status');
    Route::post('pickup_location/set-default', 'PickupLocationController@setDefault')->name('pickup_locations.set_default');
    Route::get('set/pickup_location/{id}', 'PickupLocationController@setPickupLocation')->name('pickup_locations.set');

    //carriers
    Route::resource('carriers', 'CarrierController');
    Route::post('carrier/delete', 'CarrierController@destroy')->name('carriers.destroy');
    Route::post('carrier/status', 'CarrierController@status')->name('carriers.status');
    Route::post('carrier/configuration', 'CarrierController@configuration')->name('carriers.configuration');




});

//order tracking
Route::get('/tracking/{orderId}', 'OrderSyncWithCarrierController@orderTracking');




