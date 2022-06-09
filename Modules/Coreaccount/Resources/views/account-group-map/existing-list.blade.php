@if($data)
    <ul class="res-table mt-4">
        @foreach ($data as $service)
            <li>{{ $service->flditemname }}</li>
        @endforeach
    </ul>
@endif
