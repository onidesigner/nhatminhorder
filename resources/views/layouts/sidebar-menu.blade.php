<?php

$menus = [
    [
        'url' => $app->make('url')->to('home'),
        'icon' => 'fa-home',
        'title' => 'Trang chu',
        'use' => ['customer', 'crane']
    ],
//    [
//        'url' => $app->make('url')->to('gio-hang'),
//        'icon' => 'fa-shopping-cart',
//        'title' => 'Gio Hang',
//        'use' => ['customer']
//    ],
    [
        'url' => '#',
        'icon' => 'fa-heartbeat',
        'title' => 'Vận Hành',
        'children' => [
            [
                'url' => '',
                'title' => 'Đơn hàng & Kiện hàng'
            ],
            [
                'url' => '',
                'title' => 'Mua hàng & Thanh toán'
            ]
        ]
    ],
    [
        'url' => $app->make('url')->to('nhan-vien'),
        'icon' => 'fa-user',
        'title' => 'Nhan Vien'
    ],
//            [
//                'url' => '#',
//                'icon' => 'fa-meh-o',
//                'title' => 'Khiếu nại'
//            ],
    [
        'url' => '#',
        'icon' => 'fa-money',
        'title' => 'Tài chính'
    ],
    [
        'url' => '#',
        'icon' => 'fa-gear',
        'title' => 'Hệ thống'
    ]
];

?>

<div class="sidebar-menu">
    <ul class="sidebar-nav">

        @foreach($menus as $key => $menu)

            @if(empty($menu['children']))
                <li>
                    <a href="{{$menu['url']}}">
                        <div class="icon">
                            <i class="fa {{$menu['icon']}}" aria-hidden="true"></i>
                        </div>
                        <div class="title">{{$menu['title']}}</div>
                    </a>
                </li>
            @else
                <li class="@if($key == 0) active @endif dropdown ">
                    <a href="{{$menu['url']}}" class="dropdown-toggle" data-toggle="dropdown">
                        <div class="icon">
                            <i class="fa {{$menu['icon']}}" aria-hidden="true"></i>
                        </div>
                        <div class="title">{{$menu['title']}}</div>
                    </a>
                    <div class="dropdown-menu">
                        <ul>
                            @foreach($menu['children'] as $key_children => $menu_children)
                                <li>
                                    <a href="{{$menu_children['url']}}">{{$menu_children['title']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif


        @endforeach
    </ul>
</div>