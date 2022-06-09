<div class="full-width">
    <div class="form-group">
        <div class="form-group-inner custom-13">
            <label for="" class="form-label">Past Diagnosis</label>
        </div>
    </div>
    <!-- Obstetric Diagnosis -->

    <!-- End Obstetric Diagnosis-->
    <div class="form-group">
        <div class="form-group-inner custom-11">
            <div class="past-patdiagno">
                <ul class="list-group">
                    @if(isset($past_patdiagno) and count($past_patdiagno) > 0)
                    @foreach($past_patdiagno as $past_diagno)
                    <li class="list-group-item">{{$past_diagno->fldcode}}</li>
                    @endforeach
                    @else
                    <li class="list-group-item">No Diagnosis Found</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>