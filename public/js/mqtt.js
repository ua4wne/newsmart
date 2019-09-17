$(document).ready(function(){
    //var prid='';
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
            url: "/main/config/mqttmsg",
            // success - это обработчик удачного выполнения событий
            success: function(resp) {
            }
        });
        route='NO';
    };

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
            var fData = $("form[id='form-topic']").serialize();
            $.ajax({
                type: "POST",
                data: fData,
                url: "/main/config/save-topic",
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
            var fData = $("form[id='form-topic']").serialize();
            $.ajax({
                type: "POST",
                data: fData, //{name:stopic,payload:pval,route:route,option_id:option_id},
                url: "/main/config/save-topic",
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
                url: "/main/config/del-topic",
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
                url: "/main/config/del-topic",
                // success - это обработчик удачного выполнения событий
                success: function(resp) {
                    //alert("Сервер вернул вот что: " + resp);
                    if(resp=='OK'){
                        var idx = ".sub:contains(" + stopic + ")";
                        var count = $(idx).size();
                        if(count)
                            $(idx).remove();
                        alert('Топик '+ stopic +' был снят с подписки и удален из системы!');
                    }

                }
            });
            route='NO';
        }
    }, ".subs" );
});