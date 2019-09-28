<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? '' }}</title>
    <style type="text/css">
        table {
            border-collapse: collapse; /*убираем пустые промежутки между ячейками*/
            border: 1px solid #039; /*устанавливаем для таблицы внешнюю границу серого цвета толщиной 1px*/
        }

        caption {
            text-transform: uppercase;
        }

        th {
            text-transform: uppercase;
            border: 1px solid #0865c2;
            padding: 15px;
        }

        td {
            border: 1px dashed #0865c2;
            padding: 10px 20px;
            text-align: left;
        }
    </style>

</head>

<body>
@yield('content')
</body>
</html>
