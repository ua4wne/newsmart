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

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::middleware(['auth'])->group(function(){
    Route::get('/', 'MainController@index')->name('main');

    //sysconst/ группа обработки роутов sysconst
    Route::group(['prefix'=>'sysconst'], function(){
        Route::get('/',['uses'=>'SysConstController@index','as'=>'sysconst']);
        //sysconst/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\SysConstController@create','as'=>'sconstAdd']);
        //sysconst/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\SysConstController@edit','as'=>'sconstEdit']);
        //sysconst/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\SysConstController@delete','as'=>'delSysConst']);
    });
    //locations/ группа обработки роутов locations
    Route::group(['prefix'=>'locations'], function(){
        Route::get('/',['uses'=>'LocationController@index','as'=>'location']);
        //locations/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\LocationController@create','as'=>'locationAdd']);
        //locations/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\LocationController@edit','as'=>'locationEdit']);
        //locations/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\LocationController@delete','as'=>'locationDel']);
    });
    //devtypes/ группа обработки роутов devtypes
    Route::group(['prefix'=>'devtypes'], function(){
        Route::get('/',['uses'=>'DeviceTypeController@index','as'=>'devtype']);
        //devtypes/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\DeviceTypeController@create','as'=>'devtypeAdd']);
        //devtypes/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\DeviceTypeController@edit','as'=>'devtypeEdit']);
        //devtypes/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\DeviceTypeController@delete','as'=>'devtypeDel']);
    });
    //categories/ группа обработки роутов categories
    Route::group(['prefix'=>'categories'], function(){
        Route::get('/',['uses'=>'CategoryController@index','as'=>'category']);
        //categories/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\CategoryController@create','as'=>'categoryAdd']);
        //categories/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\CategoryController@edit','as'=>'categoryEdit']);
        //categories/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\CategoryController@delete','as'=>'categoryDel']);
    });
    //cells/ группа обработки роутов cells
    Route::group(['prefix'=>'cells'], function(){
        Route::get('/',['uses'=>'CellController@index','as'=>'cell']);
        //cells/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\CellController@create','as'=>'cellAdd']);
        //cells/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\CellController@edit','as'=>'cellEdit']);
        //cells/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\CellController@delete','as'=>'cellDel']);
    });
    //units/ группа обработки роутов units
    Route::group(['prefix'=>'units'], function(){
        Route::get('/',['uses'=>'UnitController@index','as'=>'unit']);
        //units/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\UnitController@create','as'=>'unitAdd']);
        //units/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\UnitController@edit','as'=>'unitEdit']);
        //units/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\UnitController@delete','as'=>'unitDel']);
    });
    //protocols/ группа обработки роутов protocols
    Route::group(['prefix'=>'protocols'], function(){
        Route::get('/',['uses'=>'ProtocolController@index','as'=>'protocol']);
        //protocols/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\ProtocolController@create','as'=>'protocolAdd']);
        //protocols/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\ProtocolController@edit','as'=>'protocolEdit']);
        //protocols/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\ProtocolController@delete','as'=>'protocolDel']);
    });
    //materials/ группа обработки роутов materials
    Route::group(['prefix'=>'materials'], function(){
        Route::get('/',['uses'=>'MaterialController@index','as'=>'material']);
        //materials/ajax/add
        //Route::post('/ajax/add',['uses'=>'Ajax\MaterialController@create','as'=>'materialAdd']);
        //materials/add
        Route::match(['get','post'],'/add',['uses'=>'MaterialController@create','as'=>'materialAdd']);
        //materials/edit
        Route::match(['get','post'],'/edit/{id?}',['uses'=>'MaterialController@edit','as'=>'materialEdit']);
        //materials/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\MaterialController@delete','as'=>'materialDel']);
    });
});

Auth::routes();


