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

<form action="#" method="POST" class="form-horizontal form-wizard ui-formwizard" id="ss" novalidate="novalidate">

    <ul class="wizard-steps steps-4">
                @foreach($steps as $idx => $step)


                    <li class="@if($idx + 1 == $active) active @else disabled @endif">
                        <div class="single-step">
													<span class="title">
														{{ $idx + 1  }}</span>
                            <span class="circle">
													</span>
                            <span class="description">
														{{$step['title']}}
													</span>
                        </div>
                    </li>
                @endforeach
    </ul>
</form>
