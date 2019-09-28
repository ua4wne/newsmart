@extends('layouts.main')

@section('tile_widget')
    <!-- top tiles -->
    <div class="row top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-hdd-o"></i></div>
                <div class="count">{{ $hdd_info }}%</div>
                <h3>Занято на диске</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-server"></i></div>
                <div class="count">{{ $ram_info }}%</div>
                <h3>Занято памяти</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-clock-o"></i></div>
                <div class="count">{{ $uptime }}</div>
                <h3>Время работы</h3>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-tasks"></i></div>
                <div class="count">{{ $upload }}%</div>
                <h3>Загрузка системы</h3>
            </div>
        </div>
    </div>
    <!-- /top tiles -->
@endsection

@section('content')
    <!-- page content -->

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 overflow_hidden">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Управление и климат-контроль</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="locations" style="width:100%; height:270px;">{!! $tabs !!}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 overflow-auto">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-shield blue"></i> Безопасность</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="guard" style="width:100%; height:270px;">
                        <table class="table table-bordered table-striped">
                            <thead class="thin-border-bottom">
                            <tr>
                                <th>Помещение</th>
                                <th>Объект</th>
                                <th>Состояние</th>
                                <th>Дата</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 overflow_hidden">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-wifi blue"></i> WiFi контроллеры</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="devices" style="width:100%; height:270px;">
                        <table class="table table-bordered table-striped">
                            <thead class="thin-border-bottom">
                            <tr>
                                <th>
                                    <i class="fa fa-caret-right blue"></i>Наименование
                                </th>
                                <th>
                                    <i class="fa fa-caret-right blue"></i>Уровень сигнала
                                </th>
                                <th>
                                    <i class="fa fa-caret-right blue"></i>Батарея
                                </th>
                                <th>
                                    <i class="fa fa-caret-right blue"></i>Статус
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            {!! $device !!}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 overflow_hidden">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bell blue"></i> Последние события</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="eventlog" style="width:100%; height:270px;">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Тип</th>
                                <th>Статус</th>
                                <th>Событие</th>
                                <th>Создано</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($events as $k => $row)
                                <tr>
                                    @if($row->type=='info')
                                        <td class="text-center"><i class="fa fa-lg fa-info-circle blue"
                                                                   aria-hidden="true"></i></td>
                                    @else
                                        <td class="text-center"><i class="fa fa-lg fa-exclamation-triangle red"
                                                                   aria-hidden="true"></i></td>
                                    @endif
                                    @if($row->stat=='0')
                                        <td class="text-center" id="{{ $row->id }}">
                                            <button class="btn btn-success btn-xs val_edit" type="button"
                                                    title="Пометить как прочтенное"><i class="fa fa-envelope"
                                                                                       aria-hidden="true"></i></button>
                                        </td>
                                    @endif
                                    <td>{!! $row->msg !!}</td>
                                    <td>{{ $row->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/mqttws31.js"></script>
    <script src="/js/gauge.min.js"></script>
    <script src="/js/jquery.easypiechart.min.js"></script>
    <script>
        let mqtt;
        let reconnectTimeout = 2000;
        let username;
        let password;
        let host;
        let port;
        let topic = '#';
        let clid = "web_" + parseInt(Math.random() * 100, 10);
        let useTLS = false;

        function MQTTconnect() {
            mqtt = new Paho.MQTT.Client(host, Number(port), clid);
            options = {
                timeout: 3,
                useSSL: useTLS,
                cleanSession: true,
                onSuccess: onConnect,
                onFailure: function (message) {
                    alert('MQTT connect:' + message.errorMessage);
                    setTimeout(MQTTconnect, reconnectTimeout);
                }
            };

            mqtt.onConnectionLost = onConnectionLost;
            mqtt.onMessageArrived = onMessageArrived;

            if (username != null) {
                options.userName = username;
                options.password = password;
            }
            mqtt.connect(options);
        }

        function onConnect() {
            // Connection succeeded; subscribe to our topic
            mqtt.subscribe(topic, {qos: 0});
        }

        function onConnectionLost(responseObject) {
            setTimeout(MQTTconnect, reconnectTimeout);
            alert(responseObject.errorMessage);
        };

        function onMessageArrived(message) {
            let topic = message.destinationName;
            let payload = message.payloadString;

            $.date = function () {
                return new Date().toLocaleString();
            };

            $.ajax({
                type: "POST",
                data: {'topic': topic, 'data': payload, '_token': '{{ csrf_token() }}'},
                url: "{{ route('mqttMsg') }}",
                // success - это обработчик удачного выполнения событий
                success: function (resp) {
                    //alert('topic is resieved');
                }
            });
            //route='NO';
        };

        $.ajax({
            type: "POST",
            data: {'param': 'conf', '_token': '{{ csrf_token() }}'},
            url: "{{route('mqttConfig')}}",
            // success - это обработчик удачного выполнения событий
            success: function (res) {
                //alert("Сервер вернул вот что: " + res);
                if (res && res.length > 0) {
                    var obj = jQuery.parseJSON(res);
                    username = obj.login;
                    password = obj.pass;
                    host = obj.server;
                    port = obj.port;
                }
                if (username != null && password != null && host != null && port != null) {
                    MQTTconnect();
                    //alert('Connected to ' + host + ':' + port);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });

        $(document).on({
            click: function () {
                let stat = $(this).prev().val();
                let ptopic = $(this).prev().attr('name');
                if (stat == '1') {
                    var pval = '0';
                    $(this).prev().val('0');
                    $(this).children().removeClass('red');
                } else {
                    var pval = '1';
                    $(this).prev().val('1');
                    $(this).children().addClass('red')
                }
                var message = new Paho.MQTT.Message(pval);
                message.destinationName = ptopic;
                message.qos = 0;
                route = 'public';
                mqtt.send(message);

            }
        }, ".btn-app");

        $('.val_edit').click(function(){
            let id = $(this).parent().attr("id");
            let td = $(this).parent();
            $.ajax({
                type: 'POST',
                url: '{{ route('readLog') }}',
                data: {'id': id},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    if (res == 'OK') {
                        td.html('<i class="fa fa-envelope-open-o green" aria-hidden="true"></i>');
                        td.parent().hide();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });

    </script>

@endsection

