@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('counter') }}">{{ $title }}</a></li>
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
                        {!! Form::label('_year', 'Год:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('_year', $year,['class' => 'form-control','placeholder'=>'Укажите год','required'=>'required','maxlength'=>'4','id'=>'_year'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('_month', 'Месяц:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('_month',$month, $smonth, ['class' => 'form-control','required'=>'required','id'=>'_month']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('device_id', 'Счетчик:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::select('device_id',$devsel, old('device_id'), ['class' => 'form-control','required'=>'required','id'=>'deviceid']); !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('val', 'Показания счетчика:',['class'=>'col-xs-3 control-label']) !!}
                        <div class="col-xs-9">
                            {!! Form::text('val', old('val'),['class' => 'form-control','placeholder'=>'Введите показания счетчика','required'=>'required','maxlength'=>'9','id'=>'val'])!!}
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

    <h2 class="text-center">{{ $head }}</h2>
    @if($content)
        <div class="x_content">
            <div class="btn-group">
                <a href="#">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addNew"><i
                            class="fa fa-plus green" aria-hidden="true"></i> Новые показания
                    </button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <div class="row">
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Затраты, руб.</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id="graph_pie" style="width:100%; height:300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Помесячная динамика, руб.</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div id="graphbar" style="width:100%; height:300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            {!! $content !!}
            @endif
        </div>
        </div>
        <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/raphael.min.js"></script>
    <script src="/js/morris.min.js"></script>
    <script src="/js/gstatic_charts_loader.js"></script>

    <script>
        $(document).ready(function () {
            // Build the chart
            $.ajax({
                type: 'POST',
                url: '{{ route('counter_graph') }}',
                data: {'id': 'graph_pie'},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    var obj = jQuery.parseJSON(res);
                    google.charts.load('current', {'packages': ['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'counter');
                        data.addColumn('number', 'cost');
                        $.each(obj, function(key,value) {
                            data.addRow([
                                value.name,
                                parseFloat(value.price),
                            ]);
                        });
                        var options = {
                            is3D: true,
                        };
                        var chart = new google.visualization.PieChart(document.getElementById('graph_pie'));
                        chart.draw(data, options);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });

            $.ajax({
                type: 'POST',
                url: '{{ route('counter_graph') }}',
                data: {'id': 'graph_bar'},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    var data = jQuery.parseJSON(res);
                    Morris.Bar({
                        element: 'graphbar',
                        data: data,
                        xkey: 'x',
                        ykeys: ['y', 'z'],
                        labels: ['Предыдущий год', 'Текущий год']
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });


        $("#new_btn").click(function (e) {
            e.preventDefault();
            var error = 0;
            var row = '';

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
                $.ajax({
                    type: 'POST',
                    url: '{{ route('counterAdd') }}',
                    data: $('#new_val').serialize(),
                    success: function (res) {
                        //alert(res);
                        if ($.isEmptyObject(res.error)) {
                            if (res == 'ERR') alert('Ошибка добавления данных');
                            if (res == 'MORE_VAL') alert('Ошибка добавления данных. Предыдущее значение счетчика больше текущего!');
                            else {
                                $('#val').val('');
                                location.reload();
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
    </script>
@endsection
