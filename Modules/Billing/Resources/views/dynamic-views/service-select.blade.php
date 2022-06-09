
    @if($item_type == 'service')
        <select class="form-control select2-dynamic service-pack">
            <option value="">--Select--</option>

            @foreach($services as $service)

                <option value="{{ $service->flditemname }}">{{ $service->fldbillitem }} || {{$service->flditemtype}} || {{$service->flditemcost}}</option>

            @endforeach
        </select>
    @endif

    @if($item_type == 'package')
        @if($services)
            <select class="form-control select2-dynamic service-pack group-id-for-ajax">
                <option value="">--Select--</option>
                @foreach($services as $group)

                    <option value="{{ $group->fldgroup }}">{{ $group->fldgroup }}</option>
                @endforeach
            </select>
        @endif
    @endif
</div>

