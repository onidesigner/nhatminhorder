<?php
$menus = [];

if(Auth::check()){
    if(Auth::user()->section == App\User::SECTION_CUSTOMER){
        $menus = [

            [
                'url' => url('home'),
                'icon' => 'fa fa-tasks',
                'title' => 'Bảng chung',
                'active' => true
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
                'icon' => 'fa fa-tasks',
                'title' => 'Bảng chung',
                'active' => true
            ],
            [
                'url' => '#',
                'icon' => 'fa-heartbeat',
                'title' => 'Vận Hành',
                'children' => [
                    [
                        'url' => url('order'),
                        'title' => 'Đơn hàng',
                        'permission' => \App\Permission::PERMISSION_ORDER_LIST_VIEW
                    ],
                    [
                        'url' => url('packages'),
                        'title' => 'Kiện hàng',
                        'permission' => \App\Permission::PERMISSION_PACKAGE_LIST_VIEW
                    ],
                    [
                        'url' => url('package'),
                        'title' => 'Tạo kiện',
                        'permission' => \App\Permission::PERMISSION_PACKAGE_ADD
                    ],
                    [
                        'url' => url('scan'),
                        'title' => 'Quét mã vạch',
                        'permission' => \App\Permission::PERMISSION_SCAN_LIST_VIEW
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
                    ],
//                    [
//                        'url' => url('transaction/statistic'),
//                        'title' => 'Thông kê tài chính',
//                        'permission' => \App\Permission::PERMISSION_TRANSACTION_STATISTIC
//                    ],
                ]
            ],
            [
                'url' => '#',
                'icon' => 'fa-newspaper-o',
                'title' => 'Tin tức',
                'children' => [
                    [
                        'url' => url('taxonomies'),
                        'title' => 'Quản lý nhóm tin',
                        'permission' => \App\Permission::PERMISSION_MANAGER_TAXONOMY
                    ],
                    [
                        'url' => url('taxonomy'),
                        'title' => 'Tạo nhóm tin',
                        'permission' => \App\Permission::PERMISSION_MANAGER_TAXONOMY
                    ],
                    [
                        'url' => url('posts'),
                        'title' => 'Quản lý tin tức',
                        'permission' => \App\Permission::PERMISSION_MANAGER_POST
                    ],
                    [
                        'url' => url('post'),
                        'title' => 'Tạo tin tức',
                        'permission' => \App\Permission::PERMISSION_MANAGER_POST
                    ],
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
                    ],
                    [
                        'url' => url('manager_addon_link_error'),
                        'title' => 'Quản lý link đặt hàng báo lỗi',
                        'permission' => \App\Permission::PERMISSION_MANAGER_ADDON_LINK_ERROR
                    ],
                ]
            ]
        ];


    }
}

?>

@if($device == 'web')

    <?php if(count($menus)){ ?>
        <ul class='main-nav'>
            <?php foreach($menus as $key => $menu){ ?>

                <?php if(empty($menu['children'])){ ?>

                        <?php
                        if(!empty($menu['permission']) && !App\Permission::isAllow($menu['permission'])){
                            continue;
                        }
                        ?>

                        <li class='<?php if(isset($menu["active"]) && $menu["active"]){ echo 'active'; } ?>'>
                            <a href="{{$menu['url']}}">
                                <span>{{$menu['title']}}</span>
                            </a>
                        </li>

                <?php } else { ?>

                    <li>
                        <a href="{{$menu['url']}}" data-toggle="dropdown" class='dropdown-toggle'>
                            <span>{{$menu['title']}}</span>
                            <span class="caret"></span>
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
                            echo sprintf('<ul class="dropdown-menu">%s</ul>', implode('', $html));
                        }
                        ?>

                    </li>

                <?php } ?>


            <?php } ?>
        </ul>

    <?php } ?>

@endif

