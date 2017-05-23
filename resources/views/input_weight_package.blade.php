@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <form class="" onsubmit="return false;">
            <input type="text" name="packageBarcode" value="">

            <input type="text" name="original_bill">

        </form>

    </div>

@endsection

@section('css_bottom')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
@endsection

@section('js_bottom')
    @parent

    <script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}"></script>

@endsection
