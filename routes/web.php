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
Route::get('/control', ['uses' => 'ControlController@index']);

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
        //materials/add
        Route::match(['get','post'],'/add',['uses'=>'MaterialController@create','as'=>'materialAdd']);
        //materials/edit
        Route::match(['get','post'],'/edit/{id?}',['uses'=>'MaterialController@edit','as'=>'materialEdit']);
        //materials/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\MaterialController@delete','as'=>'materialDel']);
        //materials/ajax/getmaterials
        Route::get('/ajax/getmaterials',['uses'=>'Ajax\MaterialController@ajaxData','as'=>'getMaterial']);
        //materials/ajax/findimg
        Route::post('/ajax/findimg',['uses'=>'Ajax\MaterialController@ajaxImg','as'=>'findImg']);
        //materials/ajax/findcategory
        Route::post('/ajax/findcategory',['uses'=>'Ajax\MaterialController@ajaxCategory','as'=>'findCategory']);
    });
    //devices/ группа обработки роутов devices
    Route::group(['prefix'=>'devices'], function(){
        Route::get('/',['uses'=>'DeviceController@index','as'=>'device']);
        //devices/add
        Route::match(['get','post'],'/add',['uses'=>'DeviceController@create','as'=>'deviceAdd']);
        //devices/edit
        Route::match(['get','post'],'/edit/{id?}',['uses'=>'DeviceController@edit','as'=>'deviceEdit']);
        //devices/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\DeviceController@delete','as'=>'deviceDel']);
    });
    //options/ группа обработки роутов options
    Route::group(['prefix'=>'options'], function(){
        Route::get('/{id}',['uses'=>'OptionController@index','as'=>'option']);
        //options/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\OptionController@create','as'=>'optionAdd']);
        //options/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\OptionController@edit','as'=>'optionEdit']);
        //options/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\OptionController@delete','as'=>'optionDel']);
    });
    //rules/ группа обработки роутов rules
    Route::group(['prefix'=>'rules'], function(){
        Route::get('/{id}',['uses'=>'RuleController@index','as'=>'rule']);
        //rules/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\RuleController@create','as'=>'ruleAdd']);
        //rules/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\RuleController@edit','as'=>'ruleEdit']);
        //rules/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\RuleController@delete','as'=>'ruleDel']);
    });
    //tarifs/ группа обработки роутов tarifs
    Route::group(['prefix'=>'tarifs'], function(){
        Route::get('/',['uses'=>'TarifController@index','as'=>'tarif']);
        //tarifs/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\TarifController@create','as'=>'tarifAdd']);
        //tarifs/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\TarifController@edit','as'=>'tarifEdit']);
        //tarifs/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\TarifController@delete','as'=>'tarifDel']);
    });
    //counters/ группа обработки роутов counters
    Route::group(['prefix'=>'counters'], function(){
        Route::get('/',['uses'=>'CounterController@index','as'=>'counter']);
        //counters/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\CounterController@create','as'=>'counterAdd']);
        // counters/ajax/counter_graph
        Route::post('/ajax/counter_graph',['uses'=>'Ajax\CounterController@counter_graph','as'=>'counter_graph']);
    });
    //stocks/ группа обработки роутов stocks
    Route::group(['prefix'=>'stocks'], function(){
        Route::get('/',['uses'=>'StockController@index','as'=>'stock']);
        //stocks/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\StockController@create','as'=>'stockAdd']);
        //stocks/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\StockController@edit','as'=>'stockEdit']);
        //stocks/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\StockController@delete','as'=>'stockDel']);
    });
    //sms/ группа обработки роутов sms
    Route::group(['prefix'=>'sms'], function(){
        Route::match(['get','post'],'/',['uses'=>'SmsController@index','as'=>'sms']);
    });
    //mqtt/ группа обработки роутов mqtt
    Route::group(['prefix'=>'mqtt'], function(){
        Route::get('/',['uses'=>'MqttController@index','as'=>'mqtt']);
        //mqtt/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\MqttController@connect','as'=>'mqttConnect']);
        //mqtt/ajax/add/topic
        Route::post('/ajax/add_topic',['uses'=>'Ajax\MqttController@add_topic','as'=>'addTopic']);
        //mqtt/ajax/msg
        Route::post('/ajax/msg',['uses'=>'Ajax\MqttController@newmsg','as'=>'mqttMsg']);
        //mqtt/ajax/config
        Route::post('/ajax/config',['uses'=>'Ajax\MqttController@config','as'=>'mqttConfig']);
        //mqtt/ajax/del
        Route::post('/ajax/del_topic',['uses'=>'Ajax\MqttController@delete','as'=>'delTopic']);
    });
    //requests/ группа обработки роутов requests
    Route::group(['prefix'=>'requests'], function(){
        Route::get('/',['uses'=>'RequestController@index','as'=>'view_request']);
        //requests/ajax/del
        Route::post('/ajax/del',['uses'=>'Ajax\RequestController@delete','as'=>'requestDel']);
        //requests/ajax/debug
        Route::post('/ajax/debug',['uses'=>'Ajax\RequestController@debug','as'=>'debug']);
    });
    //eventlogs/ группа обработки роутов eventlogs
    Route::group(['prefix'=>'eventlogs'], function(){
        Route::match(['get','post'],'/',['uses'=>'EventLogController@index','as'=>'eventlog']);
        //eventlogs/ajax/read
        Route::post('/ajax/read',['uses'=>'Ajax\EventLogController@read','as'=>'readLog']);
    });
    //billing/ группа обработки роутов billing
    Route::group(['prefix'=>'billing'], function(){
        Route::get('/',['uses'=>'BillingController@index','as'=>'billing']);
        //billing/ajax/pie
        Route::post('/ajax/pie',['uses'=>'Ajax\BillingController@pie','as'=>'pie_graph']);
        //billing/ajax/bar
        Route::post('/ajax/bar',['uses'=>'Ajax\BillingController@bar','as'=>'bar_graph']);
    });
    //picking/ группа обработки роутов picking
    Route::group(['prefix'=>'picking'], function(){
        Route::get('/',['uses'=>'PickingController@index','as'=>'picking']);
        //picking/ajax/read
        Route::post('/ajax/read',['uses'=>'Ajax\PickingController@read','as'=>'picking_tbl']);
    });
    //report/ группа обработки роутов report
    Route::group(['prefix'=>'report'], function(){
        Route::get('/',['uses'=>'ReportController@index','as'=>'report']);
        //report/ajax/read
        Route::post('/ajax/read',['uses'=>'Ajax\ReportController@read','as'=>'data_graph']);
    });
});

Auth::routes();


