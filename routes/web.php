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
});

Auth::routes();


