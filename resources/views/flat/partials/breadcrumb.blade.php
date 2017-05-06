@if(!empty($urls))
    <ol class="breadcrumb">
        @foreach($urls as $key => $url)
            @if($key + 1 < count($urls))
                <li>
                    <a href="{{$url['link']}}">{{$url['name']}}</a>
                    <i class="icon-angle-right"></i>
                </li>
            @else
                <li class="active">{{$url['name']}}</li>
            @endif
        @endforeach
    </ol>
@endif