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
                    {!! Form::open(['url' => '#','class'=>'form-horizontal','method'=>'POST','id'=>'new_connect']) !!}

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
                        {!! Form::label('pass','Логин:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::password('pass',['class' => 'form-control','placeholder'=>'Введите пароль','required'=>'','maxlength'=>'15','id' => 'mpass']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="new_connect">Сохранить</button>
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
                    <button type="button" class="btn btn-primary" id="edit_btn">Сохранить</button>
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
                            <ul id='ws' class="list-unstyled">{{ $accepted }}</ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Опубликованные топики
                        </div>
                        <div class="panel-body">
                            <ul id='publ' class="list-unstyled">{{ $public }}</ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Подписка на топики
                        </div>
                        <div class="panel-body">
                            <ul id='subs' class="list-unstyled">{{ $subscribe }} </ul>
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
    <script src="/js/mqtt.js"></script>

@endsection
