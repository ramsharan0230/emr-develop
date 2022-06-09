<form action="{{ route('patient.menu.history.nav.selection.generate.pdf') }}" class="container" method="post">
    @csrf
    @php
    $encounterDataPatientInfo = $encounterData[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">

    <div class="form-group mb-3">
        <div class="" style="overflow-y: scroll; min-width: 200px; height: 450px;">

            @foreach($options as $key => $option)
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="{{ trim($option) }}" name="selection[]" value="{{ $option }}">
                        <label class="form-check-label" for="{{ trim($option) }}">{{ $option }}</label>
                    </div>
                </li>
            </ul>
            @endforeach

        </div>
    </div>
    <div class="float-left">
        <input type="checkbox" id="checkallSelection">
        <label for="checkallSelection">Check All</label>
    </div>
    <button type="submit" class="btn btn-primary btn-action mt-2 float-right">Generate Pdf</button>
</form>

<script>
    $('#checkallSelection').click(function() {
        $('input:checkbox').prop('checked', this.checked);
    });
</script>