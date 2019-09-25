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
                                    <i class="ace-icon fa fa-caret-right blue"></i>Наименование
                                </th>

                                <th>
                                    <i class="ace-icon fa fa-caret-right blue"></i>Уровень сигнала
                                </th>

                                <th class="hidden-480">
                                    <i class="ace-icon fa fa-caret-right blue"></i>Батарея
                                </th>
                                <th class="hidden-480">
                                    <i class="ace-icon fa fa-caret-right blue"></i>Статус
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
                    <div id="climate" style="width:100%; height:270px;"></div>
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

@endsection

