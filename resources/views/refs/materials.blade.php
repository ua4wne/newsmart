@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('material') }}">{{ $title }}</a></li>
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
                    <h4 class="modal-title">Новая запись</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '#','class'=>'form-horizontal','method'=>'POST','id'=>'new_val']) !!}

                    <div class="form-group">
                        {!! Form::label('name', 'Наименование:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('name', old('name'),['class' => 'form-control','placeholder'=>'Введите наименование','required'=>'required','maxlength'=>'100','id'=>'name'])!!}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('category_id', 'Категория:',['class'=>'col-xs-3 control-label','required'=>'required','id'=>'catid']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('category_id',$catsel, old('category_id'), ['class' => 'form-control']); !!}
                            {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('image', 'Изображение:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::file('image', ['class' => 'filestyle','data-buttonText'=>'Выберите изображение','data-buttonName'=>"btn-primary",'data-placeholder'=>"Файл не выбран",'id'=>'photo']) !!}
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
                            {!! Form::text('name', old('name'),['class' => 'form-control','placeholder'=>'Введите наименование','required'=>'required','maxlength'=>'100','id'=>'ename'])!!}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('category_id', 'Категория:',['class'=>'col-xs-2 control-label','required'=>'required','id'=>'ecatid']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('category_id',$catsel, old('category_id'), ['class' => 'form-control']); !!}
                            {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('image', 'Изображение:',['class'=>'col-xs-2 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::file('image', ['class' => 'filestyle','data-buttonText'=>'Выберите изображение','data-buttonName'=>"btn-primary",'data-placeholder'=>"Файл не выбран",'id'=>'ephoto']) !!}
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
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addNew"><i class="fa fa-plus green" aria-hidden="true"></i> Новая номенклатура </button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Наименование</th>
                    <th>Категория</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $k => $row)
                    <tr>
                        @if(empty($row->image))
                            <td>{!! Html::image('images/noimage.jpg', 'noimage', ['class'=>'img-responsive','width'=>'50px']) !!}</td>
                        @else
                            <td>{!! Html::image('images/gallery/'.$row->image,'{{ $row->image }}',['class'=>'img-responsive','width'=>'50px']) !!}</td>
                        @endif
                        <td>{{ $row->name}}</td>
                        <td>{{ $row->category->name}}</td>
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
                    url: '{{ route('materialAdd') }}',
                    data: $('#new_val').serialize(),
                    success: function (res) {
                        //alert(res);
                        if(res=='ERR') alert('Ошибка добавления данных');
                        else {
                            //location.reload();
                            myDatatable.row.add([
                                $('#photo').val(),
                                $('#name').val(),
                                $('#catid').val(),
                                res
                            ]).draw();
                            $('#photo').val('');
                            $('#name').val('');
                            $('#catid').val(),
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
                    url: '{{ route('materialEdit') }}',
                    data: $('#edit_val').serialize(),
                    success: function(res){
                        //alert(res);
                        if(res=='ERR')
                            alert('Ошибка обновления данных!');
                        else{
                            $(".modal").modal("hide");
                            row.prevAll().eq(2).text($('#ephoto').val());
                            row.prevAll().eq(1).text($('#ename').val());
                            row.prevAll().eq(0).text($('#ecatid').val());
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
                var photo = $(this).parent().parent().prevAll().eq(2).text();
                var name = $(this).parent().parent().prevAll().eq(1).text();
                var catid = $(this).parent().parent().prevAll().eq(0).text();
                $('#ename').val(name);
                $('#ephoto').val(photo);
                $('#ecatid option:selected').each(function(){
                    this.selected=false;
                });
                $("#ecatid :contains("+catid+")").attr("selected", "selected");
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
                        url: '{{ route('materialDel') }}',
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
