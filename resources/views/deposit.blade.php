@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="step">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="step" class="">
                        <a href="#step1" id="step1-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">
                            <div class="icon fa fa-shopping-cart"></div>
                            <div class="heading">
                                <div class="title">Gio Hang</div>
                                <div class="description">Buoc 1</div>
                            </div>
                        </a>
                    </li>

                    <li role="step" class="active">
                        <a href="#step3" role="tab" id="step3-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-credit-card"></div>
                            <div class="heading">
                                <div class="title">Dat coc & Thanh toan</div>
                                <div class="description">Buoc 2</div>
                            </div>
                        </a>
                    </li>
                    <li role="step" class="">
                        <a href="#step2" role="tab" id="step2-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-truck"></div>
                            <div class="heading">
                                <div class="title">NM247 tiep nhan & xu ly</div>
                                <div class="description">Buoc 3</div>
                            </div>
                        </a>
                    </li>
                    <li role="step">
                        <a href="#step4" role="tab" id="step4-tab" data-toggle="tab" aria-controls="profile">
                            <div class="icon fa fa-check"></div>
                            <div class="heading">
                                <div class="title">Nhan hang</div>
                                <div class="description">Buoc 4</div>
                            </div>
                        </a>
                    </li>
                </ul>

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

