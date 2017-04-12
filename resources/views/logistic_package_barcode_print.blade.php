<style>
    @media print {
        @page {
            size: 70mm 22mm; /* landscape */
            /* you can also specify margins here: */
            font-size: 80px;
            margin: 0;
        }
    }
</style>

<div class="content">
    <span>
        &nbsp;<strong>{{$package->logistic_package_barcode}}</strong>
    </span>
    <br>

    &nbsp;{!!$svg!!}
    {{--<img src="data:image/png;base64,{{$img_base_64}}">--}}
    <br><span>&nbsp;@if($package->isTransportStraight())CT @endif @if($package->getOrder()){{$package->getOrder()->destination_warehouse}} @endif {{$package->getWeightCalFee()}}kg</span>
</div>

<script>
    window.print();
</script>