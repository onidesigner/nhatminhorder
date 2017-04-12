<style>
    @media print {
        @page {
            size: 70mm 24mm; /* landscape */
            /* you can also specify margins here: */
            font-size: 80px;
            margin: 0;
        }
    }
</style>

<div class="content">
    <span style='
                font-family: Helvetica Neue, Helvetica, Arial, San-Serif;
                text-align: right;
                font-weight: 500;'>
        &nbsp;{{$package->logistic_package_barcode}}
    </span>
    <br>

    &nbsp;{!!$svg!!}
    {{--<img src="data:image/png;base64,{{$img_base_64}}">--}}
    <br>
    <span>@if($package->isTransportStraight())
            &nbsp;CT&nbsp;@endif
        @if($package->getOrder())&nbsp;{{$package->getOrder()->destination_warehouse}}&nbsp;@endif
        {{$package->getWeightCalFee()}}kg
    </span>
</div>

<script>
    window.print();
</script>