<table class="table card-table">
    <tbody>
    @foreach($statistic as $s)
        <tr>
            <td>{{$s['name']}}</td>
            <td class="text-right">
                <span class="text-danger">{{$s['value']}}đ</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>