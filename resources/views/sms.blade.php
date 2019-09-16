@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('sms') }}">{{ $title }}</a></li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    @if (session('status'))
        <div class="row">
            <div class="alert alert-success panel-remove">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                {{ session('status') }}
            </div>
        </div>
    @endif

    <h2 class="text-center">{{ $head }}</h2>

    <div class="x_panel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="alert alert-warning">Отправка на свой номер, зарегистрированный на сайте sms.ru более 5 смс в день
                        платная!!!
                    </div>
                </div>
                {!! Form::open(['url' => route('sms'),'class'=>'form-horizontal','method'=>'POST']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('phone','Телефон:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('phone',old('phone'),['class' => 'form-control','required'=>'required','data-inputmask'=>"'mask' : '999-999-9999'",'maxlength'=>'12','id'=>'phone'])!!}
                            {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('message','Сообщение:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('message',old('message'),['class' => 'form-control','placeholder'=>'Введите сообщение','required'=>'required','maxlength'=>'255','id'=>'message'])!!}
                            {!! $errors->first('message', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-3 control-label"></div>
                        <div class="col-xs-9">
                            <label>
                                <input type="checkbox" name="from_mail" class="js-switch" style="display: none;" data-switchery="true">
                                Отправить через почту @sms.ru
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('Отправить', ['class' => 'btn btn-primary','type'=>'submit']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/switchery.min.js"></script>
    <script src="/js/jquery.inputmask.bundle.min.js"></script>
@endsection
