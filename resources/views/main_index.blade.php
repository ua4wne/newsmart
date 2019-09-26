@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- page content -->

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Локации</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="locations" style="width:100%; height:270px;">{!! $tabs !!}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-wifi blue"></i> WiFi контроллеры</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="devices" style="width:100%; height:270px;">
                        <table class="table table-bordered table-striped">
                            <thead class="thin-border-bottom">
                            <tr>
                                <th>
                                    <i class="fa fa-caret-right blue"></i>Наименование
                                </th>

                                <th>
                                    <i class="fa fa-caret-right blue"></i>Уровень сигнала
                                </th>

                                <th class="hidden-480">
                                    <i class="fa fa-caret-right blue"></i>Батарея
                                </th>
                                <th class="hidden-480">
                                    <i class="fa fa-caret-right blue"></i>Статус
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            {!! $device !!}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Климат-контроль</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="climate" style="width:100%; height:270px;">
                        <canvas data-type="radial-gauge"
                                data-title="Комната"
                                data-min-value="0"
                                data-max-value="40"
                                data-width="150"
                                data-height="150"
                                data-value="22.3"
                                data-units="°C"
                                data-major-ticks="0,5,10,15,20,25,30,35,40"
                                data-minor-ticks="2"
                                data-highlights='[
                                    { "from": 0, "to": 10, "color": "rgba(0,0,255,.15)" },
                                    { "from": 10, "to": 15, "color": "rgba(0,100,255,.15)" },
                                    { "from": 15, "to": 25, "color": "rgba(0,255,0,.25)" },
                                    { "from": 25, "to": 30, "color": "rgba(255,100,0,.25)" },
                                    { "from": 30, "to": 40, "color": "rgba(255,0,0,.25)" }
                                ]'
                                data-stroke-ticks="false"
                                data-value-box="true"
                                data-animation-rule="bounce"
                                data-animation-duration="500"
                                data-font-value="Led"
                                data-animated-value="true"
                                data-color-needle-start="rgba(240, 128, 128, 1)"
                                data-color-needle-end="rgba(255, 160, 122, .9)"
                        ></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Системный журнал</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="eventlog" style="width:100%; height:270px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- /page content -->
@endsection

@section('user_script')
    <script src="/js/gauge.min.js"></script>
    <script>

    </script>

@endsection

