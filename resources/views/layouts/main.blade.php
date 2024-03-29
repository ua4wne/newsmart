<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>{{ $title ?? '' }}</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <!-- dataTables -->
    <link href="/css/jquery.dataTables.min.css" rel="stylesheet">
{{--    <link href="/css/buttons.bootstrap.min.css" rel="stylesheet">--}}
    <!-- Custom Theme Style -->
    <link href="/css/custom.min.css" rel="stylesheet">
    <link href="/css/switchery.min.css" rel="stylesheet">

</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        @section('left_menu')
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="{{ route('main') }}" class="site_title"><i class="fa fa-paw"></i> <span>Главная панель</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            @if(Auth::user()->image)
                                <img src="{{ Auth::user()->image }}" alt="..." class="img-circle profile_img">
                            @else
                                <img src="/images/male.png" alt="..." class="img-circle profile_img">
                            @endif
                        </div>
                        <div class="profile_info">
                            <span>Здравствуйте,</span>
                            <h2>{{ Auth::user()->fname }}</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li><a href="{{ route('main') }}"><i class="fa fa-home"></i> Рабочий стол </a></li>
                                <li><a><i class="fa fa-gears"></i> Настройки <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('sysconst') }}">Системные константы</a></li>
                                        <li><a href="{{ route('device') }}">Оборудование</a></li>
                                        <li><a href="{{ route('view_request') }}">GET-запросы</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-wifi"></i> Коммуникации <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('sms') }}">Отправка СМС</a></li>
                                        <li><a href="{{ route('mqtt') }}">MQTT</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-calculator"></i> Учет ЖКУ <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('tarif') }}">Тарифы</a></li>
                                        <li><a href="{{ route('counter') }}">Счетчики</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-cubes"></i> Мой склад <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('stock') }}">Остатки</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-bar-chart-o"></i> Отчеты <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('billing') }}">Расходы ЖКХ</a></li>
                                        <li><a href="{{ route('picking') }}">Критические остатки</a></li>
                                        <li><a href="{{ route('report') }}">Данные</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li><a><i class="fa fa-address-book"></i> Справочники <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('devtype') }}">Типы устройств</a></li>
                                        <li><a href="{{ route('protocol') }}">Типы протоколов</a></li>
                                        <li><a href="{{ route('location') }}">Локации</a></li>
                                        <li><a href="{{ route('category') }}">Категории</a></li>
                                        <li><a href="{{ route('cell') }}">Места хранения</a></li>
                                        <li><a href="{{ route('material') }}">Номенклатура</a></li>
                                        <li><a href="{{ route('unit') }}">Ед. измерения</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Отладчик" href="{{ route('view_request') }}">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Scheduler">
                            <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Журнал событий" href="{{ route('eventlog') }}">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>
    @show

    @section('top_nav')
        <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->image)
                                        <img src="{{ Auth::user()->image }}" alt="...">
                                    @else
                                        <img src="/images/male.png" alt="...">
                                    @endif
                                    {{ Auth::user()->login }}
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>


                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->
    @show
            <div class="right_col" role="main">
            @section('tile_widget')

            @endsection
            @yield('tile_widget')

    @yield('content')

        @section('footer')
        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Домовенок
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<!-- jQuery -->
<script src="/js/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/js/fastclick.js"></script>
<!-- NProgress -->
<script src="/js/nprogress.js"></script>
<!-- iCheck -->
{{--<script src="/js/icheck.min.js"></script>--}}


<!-- Custom Theme Scripts -->
<script src="/js/custom.min.js"></script>

@show

@section('user_script')
@show

</body>
</html>
