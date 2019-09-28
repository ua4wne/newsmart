@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('device') }}">Оборудование</a></li>
        <li class="active"><a href="{{ route('option',[$id]) }}">{{ $device }}</a></li>
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
                            {!! Form::hidden('device_id',$id,['class' => 'form-control','id'=>'device_id','required'=>'required']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('address', 'Адрес датчика:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('address', old('address'),['class' => 'form-control','placeholder'=>'Введите адрес','maxlength'=>'32','id'=>'address'])!!}
                            {!! $errors->first('address', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('alias', 'Параметр:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('alias', $params, old('alias'),['class' => 'form-control','required','id'=>'alias']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name','Наименование:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите наименование','required'=>'required','maxlength'=>'50','id'=>'name'])!!}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('min_val','MIN значение:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('min_val',old('min_val'),['class' => 'form-control','placeholder'=>'Введите минимальное значение','required'=>'required','maxlength'=>'9','id'=>'min_val'])!!}
                            {!! $errors->first('min_val', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('max_val','MAX значение:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('max_val',old('max_val'),['class' => 'form-control','placeholder'=>'Введите максимальное значение','required'=>'required','maxlength'=>'9','id'=>'max_val'])!!}
                            {!! $errors->first('max_val', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('unit','Ед измерения:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('unit',old('unit'),['class' => 'form-control','placeholder'=>'Введите ед измерения','maxlength'=>'7','id'=>'unit'])!!}
                            {!! $errors->first('unit', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('to_log', 'Писать в лог:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('to_log', array('1'=>'Да','0'=>'Нет'), old('is_show'),['class' => 'form-control','required','id'=>'to_log']); !!}
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
                        {!! Form::label('address', 'Адрес датчика:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('address', old('address'),['class' => 'form-control','placeholder'=>'Введите адрес','maxlength'=>'32','id'=>'eaddress'])!!}
                            {!! $errors->first('address', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('alias', 'Параметр:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('alias', $params, old('alias'),['class' => 'form-control','required','id'=>'ealias']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('name','Наименование:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('name',old('name'),['class' => 'form-control','placeholder'=>'Введите наименование','required'=>'required','maxlength'=>'50','id'=>'ename'])!!}
                            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('min_val','MIN значение:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('min_val',old('min_val'),['class' => 'form-control','placeholder'=>'Введите минимальное значение','required'=>'required','maxlength'=>'9','id'=>'emin_val'])!!}
                            {!! $errors->first('min_val', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('max_val','MAX значение:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('max_val',old('max_val'),['class' => 'form-control','placeholder'=>'Введите максимальное значение','required'=>'required','maxlength'=>'9','id'=>'emax_val'])!!}
                            {!! $errors->first('max_val', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('unit','Ед измерения:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('unit',old('unit'),['class' => 'form-control','placeholder'=>'Введите ед измерения','maxlength'=>'7','id'=>'eunit'])!!}
                            {!! $errors->first('unit', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('to_log', 'Писать в лог:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-8">
                            {!! Form::select('to_log', array('1'=>'Да','0'=>'Нет'), old('is_show'),['class' => 'form-control','required','id'=>'eto_log']); !!}
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
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addNew"><i class="fa fa-plus green" aria-hidden="true"></i> Новый параметр</button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Параметр</th>
                    <th>Адрес датчика</th>
                    <th>Значение</th>
                    <th>MIN значение</th>
                    <th>MAX значение</th>
                    <th>Ед измерения</th>
                    <th>Писать в лог</th>
                    <th>Дата обновления</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $k => $row)
                    <tr>
                        @if($row->RuleCount())
                            <td>{{ $row->name }} <span class="badge"> {{ $row->RuleCount() }}</span></td>
                        @else
                            <td>{{ $row->name }}</td>
                        @endif
                        <td>{{ $row->alias}}</td>
                        <td>{{ $row->address}}</td>
                        <td>{{ $row->val}}</td>
                        <td>{{ $row->min_val}}</td>
                        <td>{{ $row->max_val}}</td>
                        <td>{{ $row->unit}}</td>
                        @if($row->to_log)
                            <td>Да</td>
                        @else
                            <td>Нет</td>
                        @endif
                        <td>{{ $row->updated_at}}</td>
                        <td style="width:140px;">
                            <div class="form-group" role="group" id="{{ $row->id }}">
                                <button class="btn btn-success btn-sm val_edit" type="button" data-toggle="modal" data-target="#editVal" title="Редактировать запись"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                <a href="{{ route('rule',[$row->id]) }}"><button class="btn btn-info btn-sm" type="button" title="Правила"><i class="fa fa-bell-o" aria-hidden="true"></i></button></a>
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

        $('#alias').click(function(){
            $('#name').val($("#alias option:selected").text());
        });

        $('#alias').blur(function(){
            $('#name').val($("#alias option:selected").text());
        });

        $("#new_btn").click(function (e) {
            e.preventDefault();
            var error=0;
            var row = '';
            var tolog = 'Нет';
            if($('#to_log').val()==1) tolog = 'Да';

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
                    url: '{{ route('optionAdd') }}',
                    data: $('#new_val').serialize(),
                    success: function (res) {
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR') alert('Ошибка добавления данных');
                            else {
                                //location.reload();
                                myDatatable.row.add([
                                    $('#name').val(),
                                    $('#alias').val(),
                                    $('#address').val(),
                                    '0',
                                    $('#min_val').val(),
                                    $('#max_val').val(),
                                    $('#unit').val(),
                                    tolog,
                                    '',
                                    res
                                ]).draw();
                                $('#name').val('');
                                $('#address').val('');
                                $('#min_val').val('');
                                $('#max_val').val('');
                                $('#unit').val('');
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
            var tolog = 'Нет';
            if($('#eto_log').val()==1) tolog = 'Да';
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
                    url: '{{ route('optionEdit') }}',
                    data: $('#edit_val').serialize(),
                    success: function(res){
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR')
                                alert('Ошибка обновления данных!');
                            else {
                                $(".modal").modal("hide");
                                $(".print-error-msg").css('display', 'none');
                                row.prevAll().eq(8).text($('#ename').val());
                                row.prevAll().eq(7).text($('#ealias').val());
                                row.prevAll().eq(6).text($('#eaddress').val());
                                row.prevAll().eq(4).text($('#emin_val').val());
                                row.prevAll().eq(3).text($('#emax_val').val());
                                row.prevAll().eq(2).text($('#eunit').val());
                                row.prevAll().eq(1).text(tolog);
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
                var name = $(this).parent().parent().prevAll().eq(8).text();
                var alias = $(this).parent().parent().prevAll().eq(7).text();
                var address = $(this).parent().parent().prevAll().eq(6).text();
                var minval = $(this).parent().parent().prevAll().eq(4).text();
                var maxval = $(this).parent().parent().prevAll().eq(3).text();
                var unit = $(this).parent().parent().prevAll().eq(2).text();
                var tolog = $(this).parent().parent().prevAll().eq(1).text();

                $('#ename').val(name);
                $('#eaddress').val(address);
                $('#emin_val').val(minval);
                $('#emax_val').val(maxval);
                $('#eunit').val(unit);

                $('#ealias option:selected').each(function(){
                    this.selected=false;
                });
                $('#eis_show option:selected').each(function(){
                    this.selected=false;
                });

                $('#ealias option[value='+alias+']').prop('selected', true);
                if(tolog=="Да")
                    $('#eto_log option[value=1]').prop('selected', true);
                else
                    $('#eto_log option[value=0]').prop('selected', true);

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
                        url: '{{ route('optionDel') }}',
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
