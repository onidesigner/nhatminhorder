<?php

$menus = [
    [
        'url' => url('home'),
        'icon' => 'fa-home',
        'title' => 'Trang chủ',
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
                'url' => url('order'),
                'title' => 'Đơn hàng & Kiện hàng'
            ],
//            [
//                'url' => '',
//                'title' => 'Mua hàng & Thanh toán'
//            ]
        ]
    ],
    [
        'url' => url('nhan-vien'),
        'icon' => 'fa-user',
        'title' => 'Nhân viên '
    ],
//            [
//                'url' => '#',
//                'icon' => 'fa-meh-o',
//                'title' => 'Khiếu nại'
//            ],
    [
        'url' => '#',
        'icon' => 'fa-money',
        'title' => 'Tài chính',
        'children' => [
            [
                'url' => url('transactions'),
                'title' => 'Lịch sử giao dịch '
            ],
            [
                'url' => url('transaction/adjustment'),
                'title' => 'Tạo điều chỉnh tài chính '
            ]
        ]
    ],
    [
        'url' => '#',
        'icon' => 'fa-gear',
        'title' => 'Hệ thống',
        'children' => [
            [
                'url' => url('setting/roles'),
                'title' => 'Nhóm & phân quyền '
            ],
            [
                'url' => url('user/original_site'),
                'title' => 'Quản lý user mua hàng site gốc'
            ],
            [
                'url' => url('warehouses'),
                'title' => 'Quản lý kho hàng'
            ],
            [
                'url' => url('warehouses_manually'),
                'title' => 'Cấu hình kho'
            ],
            [
                'url' => url('setting'),
                'title' => 'Cấu hình chung '
            ]
        ]
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