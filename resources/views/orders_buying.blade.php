@extends('layouts.app')

@section('page_title')
    {{$page_title}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                @include('partials/__breadcrumb',
                                [
                                    'urls' => [
                                        ['name' => 'Trang chủ', 'link' => url('home')],
                                        ['name' => 'Mua hàng', 'link' => null],
                                    ]
                                ]
                            )

                <div class="card-body">

                    <h3>{{$page_title}}</h3>

                    <form
                            action="{{ url('order_buying')  }}" method="get" id="_form-orders">
                        <input type="hidden" name="page" value="{{ request()->get('page')  }}">

                        <input type="text" placeholder="Mã đơn..." name="order_code" value="{{ request()->get('order_code') }}">
                        <input type="text" placeholder="Mã khách hoặc email..."
                               class=""
                               name="customer_code_email" value="{{ request()->get('customer_code_email') }}">

                        <br><br>

                        @foreach($status_list as $status_list_item)
                            @if($status_list_item['selected'])
                                <a class="_select-order-status selected" href="javascript:void(0)" data-status="{{ $status_list_item['key'] }}">
                                    <span class="label label-danger"><i class="fa fa-times" aria-hidden="true"></i> {{ $status_list_item['val']  }}</span>
                                </a>
                            @else
                                <a class="_select-order-status" href="javascript:void(0)" data-status="{{ $status_list_item['key'] }}">
                                    <span class="label label-success">{{ $status_list_item['val']  }}</span>
                                </a>
                            @endif

                        @endforeach

                        <input type="hidden" name="status" value="{{ request()->get('status')  }}">

                    </form>
                    <br>

                    <div id="_page-content"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js_bottom')
    @parent

    <script type="text/javascript" src="{{ asset('js/jquery.lazy.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            $(document).on('click', '._select-order-status', function(){
                 var selected = $(this).hasClass('selected');
                 if(selected){
                     $(this).removeClass('selected');
                     $(this).find('span').removeClass('label-danger').addClass('label-success');
                     var text = $(this).find('span').text();
                     $(this).find('span').html(text);
                 }else{
                     $(this).addClass('selected');
                     $(this).find('span').removeClass('label-success').addClass('label-danger');
                     var text = $(this).find('span').text();
                     $(this).find('span').html('<i class="fa fa-times" aria-hidden="true"></i> ' + text);
                 }

                 var order_status_list = [];
                 $('._select-order-status.selected').each(function(){
                     order_status_list.push($(this).data('status'));
                 });

                 $('[name="status"]').val(order_status_list.join(','));

                get_orders_data(true);
            });
            get_orders_data();
        });

        $(document).on('change', '#_form-orders', function(e){
            get_orders_data(true);
        });

        $(document).on('click', 'ul.pagination > li > a', function(e){
            e.preventDefault();
            var rel = $(this).attr('rel');

            if(rel == 'prev'){
                var page = parseInt($('ul.pagination > li.active').text()) - 1;
                $('input[name="page"]').val(page);
            }else if(rel == 'next'){
                var page = parseInt($('ul.pagination > li.active').text()) + 1;
                $('input[name="page"]').val(page);
            }else{
                $('input[name="page"]').val($(this).text());
            }
            get_orders_data();
        });

        function get_orders_data(search){
            if(search){
                $('input[name="page"]').val(1);
            }

            var page_url = $('#_form-orders').attr('action') + '?' + $('#_form-orders').serialize();
            if(page_url != window.location){
                window.history.pushState({'path': page_url}, '', page_url);
            }

            request("{{ url('order_buying/get_orders_data')  }}",
                "get",
                $('#_form-orders').serializeObject())
                .done(function(response){
                    if(response.success){
                        $('#_page-content').html(response.html);
                        $('.lazy').lazy();
                    }else{
                        if(response.message){
                            bootbox.alert(response.message);
                        }
                    }
            });
        }

    </script>
@endsection

