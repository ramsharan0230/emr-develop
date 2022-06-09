<div class="accordion">
{{-- @php dd($medtype) @endphp --}}
@foreach ($result as $key => $results )

    <div class="accordion-item">
        <div class="card-header d-flex align-items-center flex-row mt-2 p-2 acc-header-contentmed" style="background-color: #e3e3e3;">
            <input class="magic-checkbox mr-2" type="checkbox" name="drugarr" value="{{ $results->flddrug }}">
            <span>{{ $results->flddrug }}</span>
        </div>

        @php 
            if($medtype == 'Medicines'){
                $multidrugs = \DB::table('tblmedbrand')->where('flddrug',$results->flddrug)->get();
            }else if($medtype == 'Surgicals'){
                
                $multidrugs = \DB::table('tblsurgbrand')->where('fldsurgid',$results->flddrug)->get();
                
            }else{
                
                $multidrugs = \DB::table('tblextrabrand')->where('fldextraid',$results->flddrug)->get();
                
            }
            
            
           
        @endphp
        <div class="acc-body" aria-labelledby="" data-parent="" data-check-open="">
            <div class="acc-body-content">
                @foreach ($multidrugs as $key => $itemname )
                    <div class="d-flex flex-row align-items-center pt-1 pb-1 pl-4 pr-2">
                        <input class="magic-checkbox mr-2" type="checkbox" name="medbrand" id="medbrand" value="{{ $itemname->fldbrandid }}">{{ $itemname->fldbrandid }}
                    </div>
                @endforeach
            </div>                                               
        </div> 
    </div>
@endforeach

<div class="mt-1 medicinepaginate">
{{$result->links()}}
</div>


</div>

<script>
    var accHeadermed = document.querySelectorAll(".acc-header-contentmed");

    //const accHeadermed = document.getElementsByClassName(".acc-header-content")

    accHeadermed.forEach( accHeadermed => {
        accHeadermed.addEventListener("click", e => {
            var currentActiveAcc = document.querySelector(".acc-header-contentmed.active");
            if(currentActiveAcc && currentActiveAcc!=accHeadermed) {
                currentActiveAcc.classList.toggle("active");
                currentActiveAcc.nextElementSibling.classList.remove("active");
            }

            accHeadermed.classList.toggle("active");              
        })
    })
</script>




