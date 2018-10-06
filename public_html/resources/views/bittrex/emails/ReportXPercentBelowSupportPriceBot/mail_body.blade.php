<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p>Hi Asif,</p>
            <p></p>
            <p>The price of following altcoions have crossed below the support price limits by 20%.</p>
            @forelse ($mailBody['notEmailedPriceList'] as $row)
                <p>Market name: <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row->MarketName }}" target="_blank">{{ $row->MarketName }}</a>; Support price: {{number_format($row->supportPrice, 8)}}; Current price: {{number_format($row->Last, 8)}}</p>
            @empty
            @endforelse
            <p></p>
            <p>Thanks</p>
            <p>CryptoBots Team</p>
        </div>
    </div>
</div>


<td>
