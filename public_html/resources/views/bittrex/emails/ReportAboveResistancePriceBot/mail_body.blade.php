<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p>Hi Asif,</p>
            <p></p>
            <p>The following altcoions have crossed the resistance limits.</p>
            @forelse ($mailBody['aboveResistancePriceList'] as $row)
                <p>Market name: <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row->MarketName }}" target="_blank">{{ $row->MarketName }}</a>; Resistance price: {{number_format($row->resistancePrice, 8)}}; Current price: {{number_format($row->Bid, 8)}}</p>
            @empty
            @endforelse
            <p></p>
            <p>Thanks</p>
            <p>CryptoBots Team</p>
        </div>
    </div>
</div>


