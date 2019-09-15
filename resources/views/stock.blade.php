@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li><a href="{{ route('stock') }}">{{ $title }}</a></li>
        <li class="active">Остатки</li>
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
                        {!! Form::label('image', 'Изображение:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-offset-2 col-xs-10">
                            {!! Html::image('images/noimage.jpg', 'noimage', ['class'=>'img-fluid, img-thumbnail, center-block, img_midi','id'=>'photo']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-8">
                            {!! Form::hidden('category','',['class' => 'form-control','id'=>'category']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('cell_id', 'Место хранения:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('cell_id', $celsel, old('cell_id'),['class' => 'form-control','required','id'=>'cell_id']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('material_id','Номенклатура:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('material_id',old('material_id'),['class' => 'form-control','placeholder'=>'Начните ввод наименования','required'=>'required','id'=>'search_input'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('quantity','Количество:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('quantity',old('quantity'),['class' => 'form-control','placeholder'=>'Введите количество','required'=>'required','maxlength'=>'11','id'=>'quantity'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('unit_id', 'Ед измерения:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('unit_id', $usel, old('unit_id'),['class' => 'form-control','required','id'=>'unit_id']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('price','Цена, руб.:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('price',old('price'),['class' => 'form-control','placeholder'=>'Укажите цену','required'=>'required','maxlength'=>'9','id'=>'price'])!!}
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
                        {!! Form::label('image', 'Изображение:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-offset-2 col-xs-10">
                            {!! Html::image('images/noimage.jpg', 'noimage', ['class'=>'img-fluid, img-thumbnail, center-block, img_midi','id'=>'ephoto']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-8">
                            {!! Form::hidden('category','',['class' => 'form-control','id'=>'ecategory']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('cell_id', 'Место хранения:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('cell_id', $celsel, old('cell_id'),['class' => 'form-control','required','id'=>'ecell_id']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('material','Номенклатура:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('material',old('material'),['class' => 'form-control','placeholder'=>'Начните ввод наименования', 'disabled'=>'disabled','id'=>'ematerial'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('quantity','Количество:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('quantity',old('quantity'),['class' => 'form-control','placeholder'=>'Введите количество','required'=>'required','maxlength'=>'11','id'=>'equantity'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('unit_id', 'Ед измерения:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('unit_id', $usel, old('unit_id'),['class' => 'form-control','required','id'=>'eunit_id']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('price','Цена, руб.:',['class' => 'col-xs-3 control-label'])   !!}
                        <div class="col-xs-9">
                            {!! Form::text('price',old('price'),['class' => 'form-control','placeholder'=>'Укажите цену','required'=>'required','maxlength'=>'9','id'=>'eprice'])!!}
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
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addNew"><i class="fa fa-plus green" aria-hidden="true"></i> Новое поступление</button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Фото</th>
                    <th>Ячейка</th>
                    <th>Номенклатура</th>
                    <th>Категория</th>
                    <th>Кол-во</th>
                    <th>Ед изм</th>
                    <th>Цена, руб</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $k => $row)
                    <tr>
                        <td style="width:80px;">{!! Html::image($row->material->image,'{{ $row->material->image }}', ['class'=>'img-responsive, img_mini']) !!}</td>
                        <td>{{ $row->cell->name }}</td>
                        <td><a href="{{ route('materialEdit',['id'=>$row->material->id]) }}" target="_blank">{{ $row->material->name }}</a></td>
                        <td>{{ $row->material->category->name }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>{{ $row->unit->name }}</td>
                        <td>{{ $row->price }}</td>
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
    <script src="/js/typeahead.min.js"></script>

    <script>
        var myDatatable = $('#my_datatable').DataTable( {
            //"order": [[ 0, "desc" ]]
        } );

        $("#unit_id :contains('шт.')").attr("selected", "selected");
        $("#eunit_id :contains('шт.')").attr("selected", "selected");
        $("#cell_id").prepend($('<option value="0">Выберите ячейку</option>'));
        $("#cell_id :first").attr("selected", "selected");
        $("#cell_id :first").attr("disabled", "disabled");

        var url_firm = "{{ route('getMaterial') }}";
        $('#search_input').typeahead({
            minLength: 3,
            source:  function (query, process) {
                return $.get(url_firm, { query: query }, function (data) {
                    return process(data);
                });
            }
        });

        $( "#quantity" ).click(function() {
            var material = $("#search_input").val();
            $.ajax({
                type: 'POST',
                url: '{{ route('findImg') }}',
                data: {'material': material},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    if(res != 'ERR')
                        $('#photo').attr("src", res);
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('findCategory') }}',
                data: {'material': material},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    if(res != 'ERR')
                        $('#category').val(res);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });

        $("#new_btn").click(function (e) {
            e.preventDefault();
            var error=0;
            var row = '';
            var tolog = 'Нет';

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
                    url: '{{ route('stockAdd') }}',
                    data: $('#new_val').serialize(),
                    success: function (res) {
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR') alert('Ошибка добавления данных');
                            else {
                                //location.reload();
                                myDatatable.row.add([
                                    "<img src='" + $('#photo').attr('src') + "' class='img-responsive, img_mini' alt='photo'>",
                                    $("#cell_id option:selected").text(),
                                    $('#search_input').val(),
                                    $('#category').val(),
                                    $('#quantity').val(),
                                    $("#unit_id option:selected").text(),
                                    $('#price').val(),
                                    res
                                ]).draw();
                                $('#search_input').val('');
                                $('#category').val('');
                                $('#quantity').val('');
                                $('#price').val('');
                                $('#photo').attr("src", '/images/noimage.jpg');
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
                    url: '{{ route('stockEdit') }}',
                    data: $('#edit_val').serialize(),
                    success: function(res){
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR')
                                alert('Ошибка обновления данных!');
                            else {
                                $(".modal").modal("hide");
                                var newsrc = $('#ephoto').attr('src');
                                $(".print-error-msg").css('display', 'none');
                                row.prevAll().eq(6).children().attr('src',newsrc),
                                row.prevAll().eq(5).text($("#ecell_id option:selected").text()),
                                row.prevAll().eq(4).text($('#ematerial').val());
                                row.prevAll().eq(3).text($('#ecategory').val());
                                row.prevAll().eq(2).text($('#equantity').val());
                                row.prevAll().eq(1).text($("#eunit_id option:selected").text());
                                row.prevAll().eq(0).text($('#eprice').val());
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
                var src = $(this).parent().parent().prevAll().eq(6).children().attr('src');
                var cell = $(this).parent().parent().prevAll().eq(5).text();
                var material = $(this).parent().parent().prevAll().eq(4).text();
                var cat = $(this).parent().parent().prevAll().eq(3).text();
                var qty = $(this).parent().parent().prevAll().eq(2).text();
                //var unit = $(this).parent().parent().prevAll().eq(1).text();
                var price = $(this).parent().parent().prevAll().eq(0).text();
                $('#eprice').val(price);
                $('#equantity').val(qty);
                $('#ecategory').val(cat);
                $('#ematerial').val(material);
                $('#ephoto').attr("src", src);

                /*$('#eunit_id option:selected').each(function(){
                    this.selected=false;
                });*/
                $('#ecell_id option:selected').each(function(){
                    this.selected=false;
                });

                //$("#eunit_id :contains("+unit+")").attr("selected", "selected");
                $("#ecell_id :contains("+cell+")").attr("selected", "selected");

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
                        url: '{{ route('stockDel') }}',
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
