@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('mqtt') }}">{{ $title }}</a></li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="row">
        <div class="alert alert-danger print-error-msg panel-remove" style="display:none">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <ul></ul>
        </div>
    </div>

    <div class="modal fade" id="addConnect" tabindex="-1" role="dialog" aria-labelledby="addConnect" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Настройка подключения к серверу MQTT</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '#', 'class'=>'form-horizontal','method'=>'POST','id'=>'new_connect']) !!}

                    <div class="form-group">
                        {!! Form::label('server','Сервер:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('server',$server,['class' => 'form-control','placeholder'=>'Сервер MQTT','maxlength'=>'32','required'=>'required','id'=>'mserver'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('port','Порт:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('port',$port,['class' => 'form-control','placeholder'=>'Укажите порт','maxlength'=>'5','required'=>'required','id'=>'mport'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('login','Логин:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('login',$login,['class' => 'form-control','placeholder'=>'Введите логин','required'=>'required','maxlength'=>'15','id'=>'mlogin'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label" for="mpass">Пароль</label>
                        <div class="col-xs-9">
                            <input type="password" id="mpass" class="form-control" name="pass" value="{{ $pass }}" required="required" maxlength="15">
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="btn_connect">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addTopic" tabindex="-1" role="dialog" aria-labelledby="addTopic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Публикация топика MQTT</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '#','class'=>'form-horizontal','method'=>'POST','id'=>'add_topic']) !!}

                    <div class="form-group">
                        {!! Form::label('topic_id', 'Топик:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('topic_id', $seltop, old('topic_id'),['class' => 'form-control','required','id'=>'topic_id']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('route', 'Тип топика:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('route', ['public'=>'Публикация', 'subscribe'=>'Подписка'], old('route'),['class' => 'form-control','required','id'=>'route']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('option_id', 'Параметр:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('option_id', $selopt, old('option_id'),['class' => 'form-control','required','id'=>'option_id']); !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="set-topic">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-danger" id="danger_msg">Error</div>
    <div class="alert alert-success" id="success_msg">Connected</div>
    <h2 class="text-center">{{ $head }}</h2>
    <div class="x_content">
            <div class="btn-group">
                <a href="#">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addConnect"><i class="fa fa-plus green" aria-hidden="true"></i> Подключение </button>
                </a>
                <a href="#">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addTopic"><i class="fa fa-comment-o green" aria-hidden="true"></i> Публикация </button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Поток данных с брокера Mosquitto
                        </div>
                        <div class="panel-body">
                            <ul id='ws' class="list-unstyled"></ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Опубликованные топики
                        </div>
                        <div class="panel-body">
                            <ul id='publ' class="list-unstyled">{!! $public !!}</ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Подписка на топики
                        </div>
                        <div class="panel-body">
                            <ul id='subs' class="list-unstyled">{!! $subscribe !!} </ul>
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

    <script>
        $('#danger_msg').hide();
        $('#success_msg').hide();

        $(".fa").hover(function() {
            $(this).css('cursor','pointer');
        }, function() {
            $(this).css('cursor','auto');
        });

        $(".sub").hover(function() {
            $(this).css('cursor','pointer');
        }, function() {
            $(this).css('cursor','auto');
        });

        $(".pub").hover(function() {
            $(this).css('cursor','pointer');
        }, function() {
            $(this).css('cursor','auto');
        });


        var mqtt;
        var reconnectTimeout = 2000;
        var username = $('#mlogin').val();
        var password = $('#mpass').val();
        var host = $('#mserver').val();
        var port = $('#mport').val();
        var topic = '#';
        var clid = "web_" + parseInt(Math.random() * 100, 10);
        var useTLS = false;
        //var cleansession = true;
        var options;
        var route='NO';

        function MQTTconnect() {
            mqtt = new Paho.MQTT.Client(host,Number(port),clid);
            options = {
                timeout: 3,
                useSSL: useTLS,
                cleanSession: true,
                onSuccess: onConnect,
                onFailure: function (message) {
                    $('#danger_msg').html("Connection failed: " + message.errorMessage + "Retrying");
                    setTimeout(MQTTconnect, reconnectTimeout);
                    $('#danger_msg').show();
                    $('#success_msg').hide();
                }
            };

            mqtt.onConnectionLost = onConnectionLost;
            mqtt.onMessageArrived = onMessageArrived;

            if (username != null) {
                options.userName = username;
                options.password = password;
            }
            //alert("Host="+ host + ", port=" + port + " username=" + username + " password=" + password);
            mqtt.connect(options);
        }

        $(window).load(function() {
            MQTTconnect();
        });

        function onConnect() {
            $('#success_msg').html('Connected to ' + host + ':' + port);
            $('#success_msg').show();
            $('#danger_msg').hide();
            // Connection succeeded; subscribe to our topic
            mqtt.subscribe(topic, {qos: 0});
            $('#topic').val(topic);
            $('#subs').prepend('<li class="sub"><pre>' + topic + '</pre></li>');
        }

        function onConnectionLost(responseObject) {
            setTimeout(MQTTconnect, reconnectTimeout);
            $('#danger_msg').html("connection lost: " + responseObject.errorMessage + ". Reconnecting");
            $('#danger_msg').show();
            $('#success_msg').hide();
        };

        function onMessageArrived(message) {
            var topic = message.destinationName;
            var payload = message.payloadString;
            var strval = topic + ' = ' + payload;
            var idx = "li.lws:contains(" + topic + ")";
            var count = $(idx).size();
            //alert('idx='+count);
            $.date = function(){
                return new Date().toLocaleString();
            };
            var ndate=$.date().replace(',','');
            strval += '\t(' + ndate + ')';
            if(count)
                $(idx).remove();
            $('#ws').prepend('<li class="lws"><pre>' + strval + '</pre></li>');
            $.ajax({
                type: "POST",
                data: {topic:topic,payload:payload,route:route},
                url: "{{ route('mqttMsg') }}",
                // success - это обработчик удачного выполнения событий
                success: function(resp) {
                }
            });
            route='NO';
        };

        $("#btn_connect").click(function (e) {
            e.preventDefault();
            var error=0;

            $("#new_connect").find(":input").each(function() {// проверяем каждое поле ввода в форме
                if($(this).attr("required")=='required'){ //обязательное для заполнения поле формы?
                    if(!$(this).val()){// если поле пустое
                        $(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
                        error=1;// определяем индекс ошибки
                    }
                    else{
                        $(this).css('border', '1px solid green');// устанавливаем рамку зеленого цвета
                    }

                }
            })
            if(error){
                alert("Необходимо заполнять все доступные поля!");
                return false;
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('mqttConnect') }}',
                    data: $('#new_connect').serialize(),
                    success: function(res){
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR')
                                alert('Ошибка обновления данных!');
                            else {
                                window.location.reload();
                            }
                        } else {
                            $(".print-error-msg").find("ul").html('');
                            $(".print-error-msg").css('display', 'block');
                            $.each(res.error, function (key, value) {
                                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
                            });
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
        });

        $("#set-topic").on("click", function(e) {
            e.preventDefault();
            //var option_id = $('#option_id');
            if($('#route').val()=='public'){
                var ptopic = $('#topic_id option:selected').text();
                var pval = '0'; //$('#payload').val();
                var message = new Paho.MQTT.Message(pval);
                message.destinationName = ptopic;
                message.qos = 0;
                route='public';
                mqtt.send(message);
                var fData = $("form[id='add_topic']").serialize();
                $.ajax({
                    type: "POST",
                    data: fData,
                    url: "{{ route('addTopic') }}",
                    // success - это обработчик удачного выполнения событий
                    success: function(res) {
                        //alert("Сервер вернул вот что: " + res);
                        if(res=="DBL")
                            alert('Топик ' + ptopic + ' уже был сохранен ранее!');
                        else{
                            var idx = "li.pub:contains(" + ptopic + ")";
                            var count = $(idx).size();
                            if(count)
                                $(idx).remove();
                            $('#publ').append('<li class="pub" id="' + res + '"><pre>' + ptopic + '<i class="fa fa-trash subs pull-right" aria-hidden="true"></i></pre></li>');
                            $('#topic_id option:selected').remove();
                            $('#option_id option:selected').remove(); //удаляем текущий выбор из селектов
                        }
                    },
                    error: function (err) {
                        alert(err);
                    }
                });
            }
            if($('#route').val()=='subscribe'){
                var stopic = $('#topic_id option:selected').text();
                var payload = 0;
                route='subscribe';
                mqtt.subscribe(stopic);
                var fData = $("form[id='add_topic']").serialize();
                $.ajax({
                    type: "POST",
                    data: fData, //{name:stopic,payload:pval,route:route,option_id:option_id},
                    url: "{{ route('addTopic') }}",
                    // success - это обработчик удачного выполнения событий
                    success: function(res) {
                        //alert("Сервер вернул вот что: " + res);
                        if(res=="DBL")
                            alert('Топик ' + stopic + ' уже был сохранен ранее!');
                        else{
                            var idx = "li.sub:contains(" + stopic + ")";
                            var count = $(idx).size();
                            if(count)
                                $(idx).remove();
                            $('#subs').append('<li class="sub" id="' + res + '"><pre>' + stopic + '<i class="fa fa-trash subs pull-right" aria-hidden="true"></i></pre></li>');
                            $('#topic_id option:selected').remove();
                            $('#option_id option:selected').remove(); //удаляем текущий выбор из селектов
                        }
                    },
                    error: function (err) {
                        alert(err);
                    }
                });
                $('#name').val('');
                route='NO';
            }
            $('#payload').val('');
        });

        $(document).on ({
            click: function() {
                var utopic=$(this).parent().text();
                var id=$(this).parent().parent().attr('id');
                mqtt.unsubscribe(utopic);
                $.ajax({
                    type: "POST",
                    data: {id:id},
                    url: "{{ route('delTopic') }}",
                    dataType: "json",
                    // success - это обработчик удачного выполнения событий
                    success: function(resp) {
                        //alert("Сервер вернул вот что: " + resp);
                        if(resp=='OK'){
                            var idx = ".pub:contains(" + utopic + ")";
                            var count = $(idx).size();
                            if(count)
                                $(idx).remove();
                            alert('Топик '+ utopic +' был снят с публикации и удален из системы!');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
                route='NO';
            }
        }, ".pubs" );

        $(document).on ({
            click: function() {
                var stopic=$(this).parent().text();
                var id=$(this).parent().parent().attr('id');
                mqtt.unsubscribe(stopic);
                $.ajax({
                    type: "POST",
                    data: {id:id},
                    url: "{{ route('delTopic') }}",
                    dataType: "json",
                    // success - это обработчик удачного выполнения событий
                    success: function(resp) {
                        alert("Сервер вернул вот что: " + resp);
                        if(resp=='OK'){
                            var idx = ".sub:contains(" + stopic + ")";
                            var count = $(idx).size();
                            if(count)
                                $(idx).remove();
                            alert('Топик '+ stopic +' был снят с подписки и удален из системы!');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
                route='NO';
            }
        }, ".subs" );

    </script>

@endsection
