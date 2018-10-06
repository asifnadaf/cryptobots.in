<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <p>Hi Asif,</p>
            <p></p>
            @if ($mailBody['marketDumps']!= null)
                <p>Bittrex Dump (50% or more down)</p>
                <table>
                    @foreach ($mailBody['marketDumps'] as $row)
                    <tr>
                        <td>Exchange Name: {{$row['exchangeName']}}</td>
                        <td>Market Name: <a href="{{URL::to('https://bittrex.com/Market/Index?MarketName=')}}{{ $row['MarketName'] }}" target="_blank">{{ $row['MarketName'] }}</a></td>
                        <td>Last: {{number_format($row['Last'], 8)}} </td>
                        <td>Previous day: {{number_format($row['PrevDay'], 8)}} </td>
                        <td>Pump: {{number_format($row['percentChange'], 2)}} </td>
                    </tr>
                    @endforeach
                </table><br/>
            @endif
            <p>Thanks</p>
            <p>CryptoBots Team</p>
        </div>
    </div>
</div>


