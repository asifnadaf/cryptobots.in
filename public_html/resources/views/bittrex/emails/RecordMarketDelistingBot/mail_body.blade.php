<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p>Hi Asif,</p>
            <p></p>
            @if ($mailBody['marketsDelistedData']!= null)
                <p>Following markets are being de-listed. Please sell remaining altcoins of these markets</p>
                <table>
                    @foreach ($mailBody['marketsDelistedData'] as $row)
                        <tr>
                            <td>Market Name: <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row['MarketName'] }}" target="_blank">{{ $row['MarketName'] }}</a></td>
                            <td>Notice: {{$row['Notice']}}</td>
                        </tr>
                    @endforeach
                </table><br/>
            @endif
            <p>Thanks</p>
            <p>CryptoBots Team</p>
        </div>
    </div>
</div>


