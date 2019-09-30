@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('picking') }}">{{ $title }}</a></li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="x_panel">
        <div class="x_title">
            <h2>Установка фильтра</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <!-- start form -->
            {!! Form::open(['url' => '#','class'=>'form-horizontal','method'=>'POST','id'=>'report']) !!}
            <div class="form-group">
                {!! Form::label('category[]', 'Выбор категории:',['class'=>'col-xs-2 control-label']) !!}
                <div class="col-xs-8">
                    {!! Form::select('category[]',$catsel, old('category'), ['class' => 'form-control','required'=>'required','id'=>'category','multiple']); !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('count', 'Осталось меньше чем, шт.:',['class'=>'col-xs-2 control-label']) !!}
                <div class="col-xs-8">
                    {!! Form::text('count', $count,['class' => 'form-control','placeholder'=>'Укажите количество','maxlength'=>'4','id'=>'count','required'=>'required'])!!}
                </div>
            </div>

            <div class="form-group">
                <button type="reset" class="btn btn-default" id="reset">Отмена</button>
                <button type="submit" class="btn btn-primary" id="view">Показать</button>
            </div>

        {!! Form::close() !!}
        <!-- end form -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Критические остатки по номенклатуре.</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="_table"></div>
                </div>
            </div>
        </div>
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

        $('#reset').click(function () {
            $('#_table').empty();
        });

        $('#view').click(function (e) {
            e.preventDefault();
            var error=0;

            if ($('#count').val()=='')
                $('#count').val('3');
            $('#_table').empty();
            $("#report").find(":input").each(function() {// проверяем каждое поле ввода в форме
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
                    url: '{{ route('picking_tbl') }}',
                    data: $('#report').serialize(),
                    success: function (res) {
                        //alert(res);
                        $('#_table').append(res);
                        $('#my_datatable').DataTable( {
                            "order": [[ 3, "asc" ]]
                        } );
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }
        });

    </script>
@endsection
