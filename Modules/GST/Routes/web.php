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

Route::middleware(['auth','admin'])->prefix('gst-setup/gst')->as('gst_tax.')->group(function() {
    Route::get('/', 'GSTController@index')->name('index')->middleware(['permission']);
    Route::get('/list', 'GSTController@list')->name('list');
    Route::post('/store', 'GSTController@store')->name('store')->middleware(['permission','prohibited_demo_mode']);
    Route::post('/update/{id}', 'GSTController@update')->name('update')->middleware(['permission','prohibited_demo_mode']);
    Route::get('/delete/{id}', 'GSTController@destroy')->name('destroy')->middleware(['permission','prohibited_demo_mode']);

    Route::get('/configuration', 'ConfigurationController@configuration')->name('configuration_index')->middleware(['permission']);
    Route::post('/configuration-update', 'ConfigurationController@configuration_update')->name('configuration_update')->middleware(['permission','prohibited_demo_mode']);
    Route::post('/get-outsite_state_gst', 'ConfigurationController@get_outsite_state_gst')->name('get_outsite_state_gst');
    Route::post('/get-outsite_state_gst_edit', 'ConfigurationController@get_outsite_state_gst_edit')->name('get_outsite_state_gst_edit');
    Route::post('/get-same_state_gst', 'ConfigurationController@get_same_state_gst')->name('get_same_state_gst');
    Route::post('/get-same_state_gst_edit', 'ConfigurationController@get_same_state_gst_edit')->name('get_same_state_gst_edit');
    Route::post('/store-group', 'ConfigurationController@storeGroup')->name('store_group');
    Route::post('/update-group', 'ConfigurationController@updateGroup')->name('update_group');
    Route::post('/delete-group', 'ConfigurationController@deleteGroup')->name('delete_group');
    Route::get('/group/{id}/edit', 'ConfigurationController@editGroup')->name('edit_group');

});
