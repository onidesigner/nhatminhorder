@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{$page_title}}
                </div>
                <div class="card-body">
                    <form class="form form-horizontal" action="{{ url('setting')  }}" method="post">
                        <div class="section">
                            <div class="section-body">

                                @foreach($data as $key => $data_item)

                                <div class="form-group">
                                    <label class="col-md-3 control-label">{{$data_item['field_name']}}</label>
                                    <div class="col-md-9">

                                        <input @if($key == 0) autofocus @endif type="text" name="{{$data_item['key']}}" class="form-control" placeholder="" value="{{ @$data_inserted[$data_item['key']]  }}">
                                    </div>
                                </div>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                @endforeach
                            </div>
                        </div>
                        <div class="form-footer">
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">


                                    <button type="submit" class="btn btn-primary">Save</button>

                                    @if($save == 'success')
                                        <span class="label label-success">Cật nhật thành công!</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_bottom')
    @parent
    <script>
        $(document).ready(function(){



        });

    </script>
@endsection

