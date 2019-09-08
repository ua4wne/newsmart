@extends('layouts.main')

@section('tile_widget')

@endsection

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('main') }}">Рабочий стол</a></li>
        <li class="active"><a href="{{ route('device') }}">{{ $title }}</a></li>
    </ul>
    <!-- END BREADCRUMB -->
    <!-- page content -->
    @if (session('status'))
        <div class="row">
            <div class="alert alert-success panel-remove">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                {{ session('status') }}
            </div>
        </div>
    @endif

    <h2 class="text-center">{{ $head }}</h2>
    @if($rows)
        <div class="x_content">
            <div class="btn-group">
                <a href="#">
                    <a href="{{ route('deviceAdd') }}">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-plus green"
                                                                                aria-hidden="true"></i> Новое устройство
                        </button>
                    </a>
                </a>
            </div>
        </div>

        <div class="x_panel">
            <table id="my_datatable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Идентификатор</th>
                    <th>Наименование</th>
                    <th>Тип</th>
                    <th>Описание</th>
                    <th>Адрес</th>
                    <th>Контроль</th>
                    <th>Состояние</th>
                    <th>Протокол</th>
                    <th>Локация</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $k => $row)
                    <tr>
                        @if(empty($row->image))
                            <td style="width:100px;">{!! Html::image('images/noimage.jpg', 'noimage', ['class'=>'img-responsive, img_mini']) !!}</td>
                        @else
                            <td style="width:100px;">{!! Html::image($row->image,'{{ $row->image }}',['class'=>'img-responsive, img_mini']) !!}</td>
                        @endif
                        <td>{{ $row->uid}}</td>
                        <td>{{ $row->name}}</td>
                        <td>{{ $row->type->name}}</td>
                        <td>{{ $row->descr}}</td>
                        <td>{{ $row->address}}</td>
                        @if($row->verify==0)
                            <td><span class="label label-danger">Ручной</span></td>
                        @else
                            <td><span class="label label-success">Автоматический</span></td>
                        @endif
                        @if($row->status==0)
                            <td><span class="label label-danger">Отключено</span></td>
                        @else
                            <td><span class="label label-success">В работе</span></td>
                        @endif
                        <td>{{ $row->protocol->name}}</td>
                        <td>{{ $row->location->name}}</td>
                        <td style="width:140px;">
                            <div class="form-group" role="group" id="{{ $row->id }}">
                                <a href="{{ route('deviceEdit',['id'=>$row->id]) }}">
                                    <button class="btn btn-success btn-sm val_edit" type="button"
                                            title="Редактировать запись"><i class="fa fa-edit" aria-hidden="true"></i>
                                    </button>
                                </a>
                                <a href="{{ route('option',['id'=>$row->id]) }}">
                                    <button class="btn btn-info btn-sm val_edit" type="button"
                                            title="Параметры"><i class="fa fa-gears" aria-hidden="true"></i>
                                    </button>
                                </a>
                                <button class="btn btn-danger btn-sm val_delete" type="button" title="Удалить запись"><i
                                        class="fa fa-trash" aria-hidden="true"></i></button>
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
        var myDatatable = $('#my_datatable').DataTable({
            //"order": [[ 0, "desc" ]]
        });

        $(document).on({
            click: function () {
                var id = $(this).parent().attr("id");
                var x = confirm("Выбранная запись будет удалена. Продолжить (Да/Нет)?");
                if (x) {
                    var btn = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('deviceDel') }}',
                        data: {'id': id},
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            //alert(res);
                            if (res == 'OK') {
                                btn.parent().parent().parent().hide();//location.reload();
                                myDatatable.draw();
                            }
                            if (res == 'NO')
                                alert('Выполнение операции запрещено!');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.status);
                            alert(thrownError);
                        }
                    });
                } else {
                    return false;
                }
            }
        }, ".val_delete");

    </script>
@endsection
