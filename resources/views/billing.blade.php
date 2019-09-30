@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('billing') }}">{{ $title }}</a></li>
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
                {!! Form::label('period', 'Выбор периода:',['class'=>'col-xs-2 control-label']) !!}
                <div class="col-xs-8">
                    {!! Form::select('period',['all'=>'За все время','sel'=>'Выбранный год'], 'all', ['class' => 'form-control','required'=>'required','id'=>'period']); !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('year', 'Год:',['class'=>'col-xs-2 control-label']) !!}
                <div class="col-xs-8">
                    {!! Form::text('year', '',['class' => 'form-control','placeholder'=>'Укажите год','disabled'=>'disabled','maxlength'=>'4','id'=>'year'])!!}
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
    <div class="row graph">
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Затраты по счетчикам, руб.</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="graph_pie" style="width:100%; height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Затраты по периодам, руб.</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="graphbar" style="width:100%; height:300px;"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/raphael.min.js"></script>
    <script src="/js/morris.min.js"></script>
    <script src="/js/gstatic_charts_loader.js"></script>
    <script>
        $('.graph').hide();
        $('#period').change(function () {
            let text = $('#period option:selected').text();
            if (text == 'Выбранный год')
                $('#year').removeAttr('disabled');
            else
                $('#year').attr('disabled', 'disabled');
        });

        $('#reset').click(function () {
            $('#year').attr('disabled', 'disabled');
            $('#graph_pie').empty();
            $('#graphbar').empty();
            $('.graph').hide();
        });

        $('#view').click(function (e) {
            e.preventDefault();
            let dt = new Date();
            let y = dt.getFullYear();
            let text = $('#period option:selected').text();
            if (text == 'Выбранный год' && $('#year').val()=='')
                $('#year').val(y);
            $('#graph_pie').empty();
            $('#graphbar').empty();
            // Build the chart
            $.ajax({
                type: 'POST',
                url: '{{ route('pie_graph') }}',
                data: $('#report').serialize(),
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
                url: '{{ route('bar_graph') }}',
                data: $('#report').serialize(),
                success: function (res) {
                    //alert(res);
                    var data = jQuery.parseJSON(res);
                    Morris.Bar({
                        element: 'graphbar',
                        data: data,
                        xkey: 'y',
                        ykeys: ['p'],
                        labels: ['Затраты']
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
            $('.graph').show();
        });

    </script>
@endsection
