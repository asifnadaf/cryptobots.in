<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p>Hi Asif,</p>
            <p></p>
            @if ($mailBody['newMarketsListedData']!= null)
                <p>The exchange has recently listed following markets</p>
                <table>
                    @foreach ($mailBody['newMarketsListedData'] as $row)
                        <tr>
                            <td>Market currency: {{$row['MarketCurrency']}}</td>
                            <td>Base currency: {{$row['BaseCurrency']}}</td>
                            <td>Market name: <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row['MarketName'] }}" target="_blank">{{ $row['MarketName'] }}</a></td>
                            <td>Created date: {{ Carbon\Carbon::parse($row['Created'],'UCT')->setTimezone('Asia/Kolkata')->format('d-m-Y h:i:s A') }}</td>
                        </tr>
                    @endforeach
                </table><br/>
            @endif
            <p>Thanks</p>
            <p>CryptoBots Team</p>
        </div>
    </div>
</div>


