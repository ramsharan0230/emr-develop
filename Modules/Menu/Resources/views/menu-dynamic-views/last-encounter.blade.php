<form action="{{ route('patient.last.encounter') }}" method="POST">
    @csrf
    @foreach ($arrayEncounter as $encid)
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" name="lastEncounter" id="encounter-{{ $encid }}" value="{{ $encid }}">
            <label for="encounter-{{ $encid }}" class="custom-control-label">{{ $encid }}</label>
        </div>
    @endforeach
    <input type="submit" class="btn btn-primary mt-2">
</form>
