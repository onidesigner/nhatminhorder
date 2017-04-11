<span style='font-weight: bold;
                                font-family: Helvetica Neue, Helvetica, Arial, San-Serif;
                                text-align: right;'>{{$package->logistic_package_barcode}}</span>
<br><img src="data:image/png;base64,{{$img_base_64}}">
<br><span>@if($package->isTransportStraight()) CT @endif @if($package->getOrder()) {{$package->getOrder()->destination_warehouse}} @endif {{$package->getWeightCalFee()}}kg</span>

<script>
    window.print();
</script>