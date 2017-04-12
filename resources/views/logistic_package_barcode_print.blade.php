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
    <span style='
                font-family: Helvetica Neue, Helvetica, Arial, San-Serif;
                font-weight: 500; display: inline-block; text-align: left; width: 100%; padding: 0 5px;'>
        {{$package->logistic_package_barcode}}
    </span>
    <br>

    <span style=" display: inline-block; text-align: left; width: 50%; padding: 0 5px;">
    {!!$svg!!}
    </span>
    
    {{--<img src="data:image/png;base64,{{$img_base_64}}">--}}
    <br>
    <span style="font-size: 11px; display: inline-block; text-align: left; width: 100%; padding: 0 5px;">
        @if($package->isTransportStraight())
            CT&nbsp;@endif
        @if($package->getOrder()){{$package->getOrder()->destination_warehouse}}&nbsp;@endif
        {{$package->getWeightCalFee()}}kg
    </span>
</div>

<script>
    window.print();
</script>