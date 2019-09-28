@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('view_request') }}">{{ $title }}</a></li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    <div class="row">
        <div class="alert alert-danger print-error-msg panel-remove" style="display:none">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <ul></ul>
        </div>
    </div>

    <h2 class="text-center">{{ $head }}</h2>
    @if($rows)
        <div class="x_content">
            <div class="btn-group">
                <a href="#">
                    @if($debug=='true')
                        <button type="button" class="btn btn-default btn-sm on_debug"><i class="fa fa-bell-o green"
                                                                                aria-hidden="true"></i> Отладка включена
                        </button>
                    @else
                        <button type="button" class="btn btn-default btn-sm off_debug"><i class="fa fa-bell-slash-o red"
                                                                                aria-hidden="true"></i> Отладка выключена
                        </button>
                    @endif
                </a>
            </div>
            <div class="btn-group">
                <a href="#">
                    <button type="button" class="btn btn-default btn-sm" id="del_btn"><i class="fa fa-trash red" aria-hidden="true"></i> Очистить лог</button>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Источник</th>
                    <th>Get- запрос</th>
                    <th>Создан</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $k => $row)
                    <tr>
                        <td>{{ $row->from }}</td>
                        <td>{{ $row->request }}</td>
                        <td>{{ $row->created_at }}</td>
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
        var myDatatable = $('#my_datatable').DataTable({
            //"order": [[ 0, "desc" ]]
        });

        $('#del_btn').click( function(e){
            e.preventDefault();
            var x = confirm("Все записи лога будут удалены. Продолжить (Да/Нет)?");
            if (x) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('requestDel') }}',
                    data: {'id': 'delete'},
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        //alert(res);
                        if (res == 'OK') {
                            location.reload();
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            } else {
                return false;
            }
        });

        $('.on_debug').click(function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ route('debug') }}',
                data: {'id': 'off'},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    if (res == 'OK') {
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });

        $('.off_debug').click(function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ route('debug') }}',
                data: {'id': 'on'},
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    //alert(res);
                    if (res == 'OK') {
                        location.reload();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        });

    </script>
@endsection
