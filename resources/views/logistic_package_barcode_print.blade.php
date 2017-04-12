<style>
    @media print {
        @page {
            size: 70mm 22mm; /* landscape */
            /* you can also specify margins here: */
            margin: 0;
            padding: 0;
        }
    }

    .padding-left-5{
        padding-left: 5px;
    }
</style>

<div class="content">
    <div style='
                font-family: Helvetica Neue, Helvetica, Arial, San-Serif;
                font-weight: 500; display: inline-block; text-align: left; width: 100%; padding: 0 5px;'>
        {{$package->logistic_package_barcode}}
    </div>

    <div style=" display: inline-block; text-align: left; width: 50%; padding: 0 5px;">
    {!!$svg!!}
    </div>
    
    <div style="font-size: 11px; display: inline-block; text-align: left; width: 100%; padding: 0 5px;">
        @if($package->isTransportStraight())
            CT
        @endif
        @if($package->getOrder()){{$package->getOrder()->destination_warehouse}} @endif
        <span class="padding-left-5">{{$package->getWeightCalFee()}}kg</span>
    </div>
</div>

<script>
    window.print();
</script>