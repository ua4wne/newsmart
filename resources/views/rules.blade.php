@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('device') }}">Оборудование</a></li>
        <li><a href="{{ route('option',[$option->device_id]) }}">{{ $option->device->name }}</a></li>
        <li><a href="{{ route('rule',[$id]) }}">{{ $option->name }}</a></li>
        <li class="active">Правила</li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="row">
        <div class="alert alert-danger print-error-msg panel-remove" style="display:none">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <ul></ul>
        </div>
    </div>

    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Новая запись</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '#','class'=>'form-horizontal','method'=>'POST','id'=>'new_val']) !!}

                    <div class="form-group">
                        <div class="col-xs-8">
                            {!! Form::hidden('option_id',$id,['class' => 'form-control','id'=>'option_id','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('condition', 'Условие на значение:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('condition', $condsel, old('condition'),['class' => 'form-control','required','id'=>'condition']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('val','Значение параметра:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('val',old('val'),['class' => 'form-control','placeholder'=>'Введите значение','required'=>'required','maxlength'=>'9','id'=>'val'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('action', 'Действие:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('action', $actsel, old('action'),['class' => 'form-control','required','id'=>'action']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('text','Текст сообщения или команда:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::textarea('text',old('text'),['class' => 'form-control','placeholder'=>'Введите текст сообщения или команду','rows'=>'3','id'=>'text'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('runtime', 'Состояние:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('runtime', array('0'=>'Отключено','1'=>'Включено'), old('runtime'),['class' => 'form-control','id'=>'runtime']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('step','Период запуска, сек:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::number('step',old('step'),['class' => 'form-control','placeholder'=>'Введите значение','required'=>'required','step'=>'300','id'=>'step'])!!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="new_btn">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editVal" tabindex="-1" role="dialog" aria-labelledby="editVal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Редактирование записи</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '#','class'=>'form-horizontal','method'=>'POST','id'=>'edit_val']) !!}

                    <div class="form-group">
                        {!! Form::label('condition', 'Условие на значение:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('condition', $condsel, old('condition'),['class' => 'form-control','required','id'=>'econdition']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('val','Значение параметра:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('val',old('val'),['class' => 'form-control','placeholder'=>'Введите значение','required'=>'required','maxlength'=>'9','id'=>'eval'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('action', 'Действие:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('action', $actsel, old('action'),['class' => 'form-control','required','id'=>'eaction']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('text','Текст сообщения или команда:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::textarea('text',old('text'),['class' => 'form-control','placeholder'=>'Введите текст сообщения или команду','rows'=>'3','id'=>'etext'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('runtime', 'Состояние:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('runtime', array('0'=>'Отключено','1'=>'Включено'), old('runtime'),['class' => 'form-control','id'=>'eruntime']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('step','Период запуска, сек:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::number('step',old('step'),['class' => 'form-control','placeholder'=>'Введите значение','required'=>'required','step'=>'300','id'=>'estep'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-8">
                            {!! Form::hidden('id_val','',['class' => 'form-control','id'=>'id_val','required'=>'required']) !!}
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
    <h2 class="text-center">{{ $head }}</h2>
    @if($rows)
        <div class="x_content">
            <div class="btn-group">
                <a href="#">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addNew"><i class="fa fa-plus green" aria-hidden="true"></i> Новое правило</button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Условие</th>
                    <th>Значение</th>
                    <th>Действие</th>
                    <th>Текст сообщения (команда)</th>
                    <th>Статус</th>
                    <th>Период, сек.</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $k => $row)
                    <tr>
                        <td>{{ $row->condition }}</td>
                        <td>{{ $row->val}}</td>
                        <td>{{ $row->action}}</td>
                        <td>{{ $row->text}}</td>
                        @if($row->runtime==0)
                            <td><span class="label label-danger">Отключено</span></td>
                        @else
                            <td><span class="label label-success">Включено </span></td>
                        @endif
                        <td>{{ $row->step}}</td>
                        <td style="width:100px;">
                            <div class="form-group" role="group" id="{{ $row->id }}">
                                <button class="btn btn-success btn-sm val_edit" type="button" data-toggle="modal" data-target="#editVal" title="Редактировать запись"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                <button class="btn btn-danger btn-sm val_delete" type="button" title="Удалить запись"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @endif
        </div>
        </div>
        <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/jquery.dataTables.min.js"></script>

    <script>
        var myDatatable = $('#my_datatable').DataTable( {
            //"order": [[ 0, "desc" ]]
        } );

        $("#new_btn").click(function (e) {
            e.preventDefault();
            var error=0;
            var row = '';
            var rtime = '<span class="label label-danger">Отключено</span>';
            if($('#runtime').val()==1) rtime = '<span class="label label-success">Включено </span>';

            $("#new_val").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('ruleAdd') }}',
                    data: $('#new_val').serialize(),
                    success: function (res) {
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR') alert('Ошибка добавления данных');
                            else {
                                //location.reload();
                                myDatatable.row.add([
                                    $('#condition').val(),
                                    $('#val').val(),
                                    $('#action').val(),
                                    $('#text').val(),
                                    rtime,
                                    $('#step').val(),
                                    res
                                ]).draw();
                                $('#val').val('');
                                $('#text').val('');
                                $('#step').val('');
                                $(".modal").modal("hide");
                                $(".print-error-msg").css('display', 'none');
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

        $('#edit_btn').click(function(e){
            e.preventDefault();
            var error=0;
            var rtime = 'Отключено';
            if($('#eruntime').val()==1) rtime = 'Включено';
            $("#edit_val").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('ruleEdit') }}',
                    data: $('#edit_val').serialize(),
                    success: function(res){
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR')
                                alert('Ошибка обновления данных!');
                            else {
                                $(".modal").modal("hide");
                                $(".print-error-msg").css('display', 'none');
                                row.prevAll().eq(5).text($('#econdition').val());
                                row.prevAll().eq(4).text($('#eval').val());
                                row.prevAll().eq(3).text($('#eaction').val());
                                row.prevAll().eq(2).text($('#etext').val());
                                row.prevAll().eq(1).text(rtime);
                                row.prevAll().eq(0).text($('#estep').val());
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

        $(document).on ({
            click: function() {
                var id = $(this).parent().attr("id");
                var cond = $(this).parent().parent().prevAll().eq(5).text();
                var eval = $(this).parent().parent().prevAll().eq(4).text();
                var action = $(this).parent().parent().prevAll().eq(3).text();
                var text = $(this).parent().parent().prevAll().eq(2).text();
                var rtime = $(this).parent().parent().prevAll().eq(1).text();
                var step = $(this).parent().parent().prevAll().eq(0).text();

                $('#eval').val(eval);
                $('#etext').val(text);
                $('#estep').val(step);

                $('#econdition option:selected').each(function(){
                    this.selected=false;
                });
                $('#eaction option:selected').each(function(){
                    this.selected=false;
                });
                $('#eruntime option:selected').each(function(){
                    this.selected=false;
                });

                $("#econdition :contains("+cond+")").attr("selected", "selected");
                //$("#eaction :contains("+action+")").attr("selected", "selected");
                $("#eaction option[value='"+action+"']").attr("selected", "selected");
                $("#eruntime :contains("+rtime+")").attr("selected", "selected");
//alert(action);
                $('#id_val').val(id);
                row = $(this).parent().parent();
            }
        }, ".val_edit" );

        $(document).on ({
            click: function() {
                var id = $(this).parent().attr("id");
                var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
                if (x) {
                    var btn = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('ruleDel') }}',
                        data: {'id':id},
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res){
                            //alert(res);
                            if(res=='OK'){
                                btn.parent().parent().parent().hide();//location.reload();
                                myDatatable.draw();
                            }
                            if(res=='NO')
                                alert('Выполнение операции запрещено!');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                        }
                    });
                }
                else {
                    return false;
                }
            }
        }, ".val_delete" );

    </script>
@endsection
