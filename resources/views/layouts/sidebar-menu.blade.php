<?php

if(Auth::user()->section == App\User::SECTION_CUSTOMER){
    $menus = [
        [
            'url' => url('home'),
            'icon' => 'fa-home',
            'title' => 'Trang chủ',
        ],
        [
            'url' => url('don-hang'),
            'icon' => 'fa-cubes',
            'title' => 'Đơn hàng',
        ],
        [
            'url' => url('giao-dich'),
            'icon' => 'fa-money',
            'title' => 'Giao dịch',
        ],
    ];
}else{
    $menus = [
        [
            'url' => url('home'),
            'icon' => 'fa-home',
            'title' => 'Trang chủ',
        ],
        [
            'url' => '#',
            'icon' => 'fa-heartbeat',
            'title' => 'Vận Hành',
            'children' => [
                [
                    'url' => url('order'),
                    'title' => 'Đơn hàng & Kiện hàng',
                    'permission' => \App\Permission::PERMISSION_ORDER_LIST_VIEW
                ],
            ]
        ],
        [
            'url' => '#',
            'icon' => 'fa-user',
            'title' => 'Nhân viên',
            'children' => [
                [
                    'url' => url('user'),
                    'title' => 'Quản lý nhân viên',
                    'permission' => \App\Permission::PERMISSION_USER_VIEW_LIST
                ],
            ]
        ],
        [
            'url' => '#',
            'icon' => 'fa-money',
            'title' => 'Tài chính',
            'children' => [
                [
                    'url' => url('transactions'),
                    'title' => 'Lịch sử giao dịch',
                    'permission' => \App\Permission::PERMISSION_TRANSACTION_VIEW
                ],
                [
                    'url' => url('transaction/adjustment'),
                    'title' => 'Tạo điều chỉnh tài chính',
                    'permission' => \App\Permission::PERMISSION_TRANSACTION_CREATE
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
                    'title' => 'Nhóm & phân quyền',
                    'permission' => \App\Permission::PERMISSION_VIEW_LIST_ROLE
                ],
                [
                    'url' => url('user/original_site'),
                    'title' => 'Quản lý user mua hàng site gốc',
                    'permission' => \App\Permission::PERMISSION_MANAGER_USER_ORIGINAL_SITE
                ],
                [
                    'url' => url('warehouses'),
                    'title' => 'Quản lý kho hàng',
                    'permission' => \App\Permission::PERMISSION_MANAGER_WAREHOUSE
                ],
                [
                    'url' => url('warehouses_manually'),
                    'title' => 'Cấu hình kho',
                    'permission' => \App\Permission::PERMISSION_MANAGER_WAREHOUSE_MANUALLY_VIEW
                ],
                [
                    'url' => url('setting'),
                    'title' => 'Cấu hình chung',
                    'permission' => \App\Permission::PERMISSION_UPDATE_SYSTEM_CONFIG
                ]
            ]
        ]
    ];


}

?>

<div class="sidebar-menu">
    <ul class="sidebar-nav">

        <?php if(count($menus)){ ?>
            <?php foreach($menus as $key => $menu){ ?>

                <?php if(empty($menu['children'])){ ?>
                    <?php
                    if(!empty($menu['permission']) && !App\Permission::isAllow($menu['permission'])){
                        continue;
                    }
                    ?>

                    <li>
                        <a href="{{$menu['url']}}">
                            <div class="icon">
                                <i class="fa {{$menu['icon']}}" aria-hidden="true"></i>
                            </div>
                            <div class="title">{{$menu['title']}}</div>
                        </a>
                    </li>
                <?php } else { ?>

                    <li class="<?php if($key == 0) { echo 'active'; } ?> dropdown">
                        <a href="{{$menu['url']}}" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="icon">
                                <i class="fa {{$menu['icon']}}" aria-hidden="true"></i>
                            </div>
                            <div class="title">{{$menu['title']}}</div>
                        </a>

                            <?php
                                $html = [];
                                foreach($menu['children'] as $key_children => $menu_children){
                                    if(!empty($menu_children['permission']) && !App\Permission::isAllow($menu_children['permission'])){
                                        continue;
                                    }

                                    $html[] = sprintf('<li><a href="%s">%s</a></li>', $menu_children['url'], $menu_children['title']);

                                    ?>

                                <?php } ?>

                            <?php
                                if(count($html)){
                                    echo sprintf('<div class="dropdown-menu"><ul>%s</ul></div>', implode('', $html));
                                }
                            ?>

                    </li>
                <?php } ?>


            <?php } ?>
        <?php } ?>
    </ul>
</div>


