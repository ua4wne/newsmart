@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('device') }}">Оборудование</a></li>
        <li class="active">{{ $title }}</li>
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

    <div class="x_panel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <a href="{{ route('device') }}"><i class="fa fa-times-circle fa-lg"
                                                             aria-hidden="true"></i></a>
                    </button>
                    <h4 class="modal-title">Редактирование записи</h4>
                </div>
                {!! Form::open(['url' => route('deviceEdit',['id'=>$data['id']]),'class'=>'form-horizontal','method'=>'POST','id'=>'new_val','enctype'=>'multipart/form-data']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('old_image', 'Изображение:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-offset-2 col-xs-10">
                            @if(empty($data->image))
                                {!! Html::image('images/noimage.jpg', 'noimage', ['class'=>'img-fluid, img-thumbnail, center-block, img_midi']) !!}
                            @else
                                {!! Html::image($data->image,'$data[\'image\']',['class'=>'img-fluid, img-thumbnail, center-block, img_midi']) !!}
                            @endif
                            {!! Form::hidden('old_image', $data->image) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('uid', 'Идентификатор:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('uid', $data->uid,['class' => 'form-control','placeholder'=>'Введите уникальный идентификатор','required'=>'required','maxlength'=>'16','id'=>'uid'])!!}
                            {!! $errors->first('uid', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name', 'Наименование:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('name', $data->name,['class' => 'form-control','placeholder'=>'Введите наименование','required'=>'required','maxlength'=>'70','id'=>'name'])!!}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('descr','Описание:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::textarea('descr',$data->descr,['class' => 'form-control','placeholder'=>'Комментарий','rows'=>'3','id'=>'descr'])!!}
                            {!! $errors->first('descr', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('address', 'Адрес:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('address', $data->address,['class' => 'form-control','placeholder'=>'Введите адрес','maxlength'=>'32','id'=>'address'])!!}
                            {!! $errors->first('address', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('type_id', 'Тип устройства:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('type_id',$typesel, $data->type_id, ['class' => 'form-control','required'=>'required','id'=>'typeid']); !!}
                            {!! $errors->first('type_id', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('protocol_id', 'Протокол:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('protocol_id',$protsel, $data->protocol_id, ['class' => 'form-control','required'=>'required','id'=>'protocolid']); !!}
                            {!! $errors->first('protocol_id', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('location_id', 'Локация:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('location_id',$locsel, $data->location_id, ['class' => 'form-control','required'=>'required','id'=>'locationid']); !!}
                            {!! $errors->first('location_id', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('verify', 'Контроль:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('verify',['0'=>'Ручной','1'=>'Автоматический'], $data->verify, ['class' => 'form-control','required'=>'required','id'=>'verify']); !!}
                            {!! $errors->first('verify', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('status', 'Состояние:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('status',['0'=>'Отключено','1'=>'В работе'], $data->status, ['class' => 'form-control','required'=>'required','id'=>'status']); !!}
                            {!! $errors->first('status', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('image', 'Изображение:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::file('image', ['class' => 'filestyle','data-buttonText'=>'Выберите изображение','data-buttonName'=>"btn-primary",'data-placeholder'=>"Файл не выбран",'id'=>'photo']) !!}
                            {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="{{ route('device') }}">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    </a>
                    <button type="submit" class="btn btn-primary" id="new_btn">Сохранить</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')

    <script>
        $("#new_btn").click(function () {
            //e.preventDefault();
            var error = 0;

            $("#new_val").find(":input").each(function () {// проверяем каждое поле ввода в форме
                if ($(this).attr("required") == 'required') { //обязательное для заполнения поле формы?
                    if (!$(this).val()) {// если поле пустое
                        $(this).css('border', '1px solid red');// устанавливаем рамку красного цвета
                        error = 1;// определяем индекс ошибки
                    } else {
                        $(this).css('border', '1px solid green');// устанавливаем рамку зеленого цвета
                    }

                }
            })
            if (error) {
                alert("Необходимо заполнять все доступные поля!");
                return false;
            } else {
                return true;
            }
        });

    </script>
@endsection
