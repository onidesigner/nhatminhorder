<?php

$steps = [
    [
        'title' => 'Giỏ hàng',
        'description' => 'Bước 1 '
    ],
    [
        'title' => 'Đặt cọc & Thanh toán',
        'description' => 'Bước 2 '
    ],
    [
        'title' => 'NhậtMinh247 tiếp nhận & xử lý',
        'description' => 'Bước 3 '
    ],
    [
        'title' => 'Nhận hàng',
        'description' => 'Bước 4 '
    ],
];

?>




    <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">

                @foreach($steps as $idx => $step)
                    <li class="@if($idx + 1 == $active) active @else disabled @endif">
                        <a href="#step-{{ $idx + 1  }}">
                            <h4 class="list-group-item-heading">{{$step['title']}}</h4>
                            <p class="list-group-item-text">{{$step['description']}}</p>
                        </a>
                    </li>
                @endforeach
            </ul>

        </div>
