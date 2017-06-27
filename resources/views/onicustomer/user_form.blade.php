@extends($layout)

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            @if (count($errors) > 0)
                <div class = "col-xs-12 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-md-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Thành viên #{{$user->code}}</h5>
                        <div class="ibox-tools">
                            <a href="{{ url('user/edit', $user_id)  }}" class="collapse-link">
                                <i class="fa fa-pencil-square"></i> Sửa thông tin
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="ibox-content no-padding border-left-right">
                            <img alt="image" class="img-responsive" src="http://webapplayers.com/inspinia_admin-v2.7/img/profile_big.jpg">
                        </div>
                        <div class="ibox-content profile-content">
                            <h4><strong>{{$user->name}}</strong></h4>
                            <p><i class="fa fa-check-square-o"></i> Trạng thái: {{ App\User::getStatusName($user->status) }}</p>
                            <p><i class="fa fa-calendar"></i> Tham gia: {{ App\Util::formatDate($user->created_at)  }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Sửa thông tin</h5>
                    </div>
                    <div>
                        <div class="ibox-content">
                            <?php
                            $section_metadata = ['class' => 'select2 form-control'];
                            if($user['section'] == App\User::SECTION_CRANE):
                                $section_metadata['disabled'] = 'disabled';
                            endif;

                            echo Form::open(array('url' => url( 'user/edit/' . $user_id ), 'class' => 'form-horizontal'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Họ & tên: </label>
                                <div class="col-sm-9">
                                    {{ Form::text('name', $user['name'], ['class' => 'form-control', 'placeholder' => 'Họ & tên']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Mật khẩu: </label>
                                <div class="col-sm-9">
                                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Mật khẩu']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-9">
                                    {{ Form::submit('Cập nhật', ['class' => 'btn btn-primary']) }}
                                    {{ Form::reset('Hủy bỏ', ['class' => 'btn btn-default']) }}
                                </div>
                           </div>
                            {{ Form::hidden('id', $user_id) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

