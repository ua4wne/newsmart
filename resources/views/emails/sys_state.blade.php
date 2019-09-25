<p><strong>UPTIME:&nbsp;{{ $uptime }}</strong></p>
<p><strong>UPLOAD:&nbsp;{{ $upload }}%</strong></p>

<table>
    <caption>ЗАГРУЗКА СИСТЕМЫ</caption>
    <tr><th>Параметр</th><th>Занято, Гб</th><th>Всего, Гб</th><th>Использовано, %</th></tr>
    {!! $content !!}
</table>
<br>
<p style="color:brown;">Сообщение отправлено почтовым роботом<br>
    системы умного дома <strong>"Домовенок"</strong></p>
