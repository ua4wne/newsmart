@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('report') }}">{{ $title }}</a></li>
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
                {!! Form::label('from', 'Начало периода:',['class'=>'col-xs-2 control-label']) !!}
                <div class="col-xs-8">
                    {{ Form::date('from', \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d')),['class' => 'form-control','required'=>'required','id'=>'from']) }}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('to', 'Конец периода:',['class'=>'col-xs-2 control-label']) !!}
                <div class="col-xs-8">
                    {{ Form::date('to', \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d')),['class' => 'form-control','required'=>'required','id'=>'to']) }}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('param', 'Выбор параметра:',['class'=>'col-xs-2 control-label']) !!}
                <div class="col-xs-8">
                    {!! Form::select('param',$paramsel, old('param'), ['class' => 'form-control','required'=>'required','id'=>'param']); !!}
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
                    <h2>Данные</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="graphbar" style="height: 500px"></div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/gstatic_charts_loader.js"></script>
    <script>
        $('.graph').hide();

        $('#reset').click(function () {
            $('#graphbar').empty();
            $('.graph').hide();
        });

        $('#view').click(function (e) {
            e.preventDefault();
            var error=0;
            $('#graphbar').empty();
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
                    url: '{{ route('data_graph') }}',
                    data: $('#report').serialize(),
                    success: function (res) {
                        //alert(res);
                        var obj = jQuery.parseJSON(res);
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = new google.visualization.DataTable();
                            data.addColumn('string', 'Дата');
                            data.addColumn('number', 'Значение');
                            $.each(obj, function(key,value) {
                                data.addRow([
                                    value.date,
                                    parseFloat(value.value),
                                ]);
                            });
                            var options = {
                                title: 'График временных изменений',
                                curveType: 'function',
                                legend: { position: 'top' }
                            };
                            var chart = new google.visualization.LineChart(document.getElementById('graphbar'));
                            chart.draw(data, options);
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
