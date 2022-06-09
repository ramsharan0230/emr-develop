<form action="{{ route('inpatient.last.encounter') }}" method="POST">
    @csrf
    @php $i    = 1; @endphp
    @foreach ($arrayEncounter as $encid)
        <div class="form-check">
            <input type="radio" class="form-check-input" name="lastEncounter" id="encounter-{{ $i }}" value="{{ $encid }}">
            <label for="encounter-{{ $i }}">{{ $encid }}</label>
        </div>
        @php $i++ @endphp

    @endforeach
    <input type="submit" class="btn btn-primary">
</form>
