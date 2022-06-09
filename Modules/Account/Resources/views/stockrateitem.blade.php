 {{-- @php dd($result) @endphp --}}


@foreach ($result as $key => $results )

    <div class="accordion-item">
        <div class="card-header d-flex align-items-center flex-row mt-2 p-2 acc-header-content" style="background-color: #e3e3e3;">
            <input class="magic-checkbox mr-2" type="checkbox" name="stockname" id="stockname" value="{{ $results->flditemname }}">
            <span>{{ $results->flditemname }}</span>
        </div>

    @php 
        $multidrugs = \DB::table('tblstockrate')->where('flditemname',$results->flditemname)->whereRaw('fldstockid is not Null')
                ->whereRaw('flddrug is not Null')->get();
        //dd($multidrugs);
    @endphp

    {{-- @foreach($key as $items) --}}
    {{-- @if($results) --}}

    {{-- @php dd($key) @endphp --}}

    <div class="acc-body" aria-labelledby="" data-parent="" data-check-open="">
        <div class="acc-body-content">


    

    {{-- @foreach ($results as $key => $itemname ) --}}

    @foreach ($multidrugs as $key => $itemname )

    {{-- @php dd($key) @endphp --}}


    {{-- @if($itemname->fld == 'fldstockid') --}}

    
            <div class="d-flex flex-row align-items-center pt-1 pb-1 pl-4 pr-2">
                <input class="magic-checkbox mr-2" type="checkbox" name="stocknamebrand" value="{{ $itemname->flddrug }}">{{ $itemname->flddrug }}
                {{-- <div class="">{{ $itemname }}</div> --}}
            </div>
           
       
    
    {{-- @endif --}}
    {{-- @endif --}}
    @endforeach
    {{-- @endforeach --}}

     </div>                                               
        </div> 


    
@endforeach

<div class="mt-1 stockratepaginate">
    {{$result->links()}}
</div>

</div>


<script>
    var accHeader = document.querySelectorAll(".acc-header-content");

    //const accHeader = document.getElementsByClassName(".acc-header-content")

    accHeader.forEach( accHeader => {
        accHeader.addEventListener("click", e => {
            var currentActiveAcc = document.querySelector(".acc-header-content.active");
            if(currentActiveAcc && currentActiveAcc!=accHeader) {
                currentActiveAcc.classList.toggle("active");
                currentActiveAcc.nextElementSibling.classList.remove("active");
            }

            accHeader.classList.toggle("active");              
        })
    })
</script>




