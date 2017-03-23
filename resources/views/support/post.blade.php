@extends('layouts/support')

@section('content')
    <!-- Page Heading -->
    <h1 class="my-4">Page Heading <small>Secondary Text</small></h1>

    <!-- Project Four -->
    <div class="row">
        <div class="col-md-12">
            <h3>
                {{ $post->post_title  }}
            </h3>
            <p>
                {{ $post->post_excerpt  }}
            </p>
            <div>
                Người đăng: <strong>{{$author->name}}</strong>, lúc <time>{{ App\Util::formatDate($post->created_at)  }}</time>
            </div>

            <br>
            {!! $post->post_content !!}
        </div>
    </div>
    <!-- /.row -->

    <hr>
@endsection