@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('location') }}">{{ $title }}</a></li>
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

    <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title">Новая локация</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '#','class'=>'form-horizontal','method'=>'POST','id'=>'new_val']) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Наименование:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('name', old('name'),['class' => 'form-control','placeholder'=>'Введите наименование','required'=>'required','maxlength'=>'50','id'=>'nameloc'])!!}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('alias','Код (EN):',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('alias',old('alias'),['class' => 'form-control','placeholder'=>'Введите значение кода','required'=>'required','maxlength'=>'50','id'=>'alias'])!!}
                            {!! $errors->first('alias', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('is_show', 'Показывать:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('is_show', array('1'=>'Да','0'=>'Нет'), old('is_show'),['class' => 'form-control','required','id'=>'is_show']); !!}
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
                        {!! Form::label('name', 'Наименование:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('name', old('name'),['class' => 'form-control','placeholder'=>'Введите наименование','required'=>'required','maxlength'=>'50','id'=>'ename'])!!}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('alias','Код (EN):',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('alias',old('alias'),['class' => 'form-control','placeholder'=>'Введите значение кода','required'=>'required','maxlength'=>'50','id'=>'ealias'])!!}
                            {!! $errors->first('alias', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('is_show', 'Показывать:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('is_show', array('1'=>'Да','0'=>'Нет'), old('is_show'),['class' => 'form-control','required','id'=>'eis_show']); !!}
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
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addNew"><i class="fa fa-plus green" aria-hidden="true"></i> Новая локация</button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Текстовый код (EN)</th>
                    <th>Отображать на сайте</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $k => $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->alias}}</td>
                        @if($row->is_show)
                            <td>Да</td>
                        @else
                            <td>Нет</td>
                        @endif
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
            var show = 'Нет';
            if($('#is_show').val()==1) show = 'Да';

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
                    url: '{{ route('locationAdd') }}',
                    data: $('#new_val').serialize(),
                    success: function (res) {
                        //alert(res);
                        if(res=='ERR') alert('Ошибка добавления данных');
                        else {
                            //location.reload();
                            myDatatable.row.add([
                                $('#nameloc').val(),
                                $('#alias').val(),
                                show,
                                res
                            ]).draw();
                            $('#nameloc').val('');
                            $('#alias').val('');
                            $(".modal").modal("hide");
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
            var show = 'Нет';
            if($('#eis_show').val()==1) show = 'Да';
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
                    url: '{{ route('locationEdit') }}',
                    data: $('#edit_val').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка обновления данных!');
                        else{
                            $(".modal").modal("hide");
                            row.prevAll().eq(2).text($('#ename').val());
                            row.prevAll().eq(1).text($('#ealias').val());
                            row.prevAll().eq(0).text(show);
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
                var location = $(this).parent().parent().prevAll().eq(2).text();
                var alias = $(this).parent().parent().prevAll().eq(1).text();
                var isshow = $(this).parent().parent().prevAll().eq(0).text();
                $('#ename').val(location);
                $('#ealias').val(alias);
                $('#eis_show option:selected').each(function(){
                    this.selected=false;
                });

                $("#eis_show :contains("+isshow+")").attr("selected", "selected");

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
                        url: '{{ route('locationDel') }}',
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
