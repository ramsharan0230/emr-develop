<div class="table table-bordered">
    <table style="width: 100%" id="sum">
        {{-- <div id="sum"> --}}
            @if($summary->isNotEmpty())
                <tr>
                    {{-- @dd($summary->sum('itemamt')) --}}
                    <td>Subtotal: Rs. {{ $summary->sum('itemamt')  }} </td>
                    <td>Tax: Rs. {{ $summary->sum('itetaxamtmamt')  }}</td>
                    <td>Discount: Rs. {{ $summary->sum('dscamt')  }}</td>
                    <td>Total Amount: Rs. {{  $summary->sum('recvamt') }}</td>
                </tr>
            @endif
    </table>
</div>