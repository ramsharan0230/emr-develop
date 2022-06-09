<div id="accordion-{{ $parentId }}">
    <div class="card">
        @if($groups)
            @forelse($groups as $child)
                <div>
                    <div class="card-header" id="heading{{ $child->GroupId }}">
                        <h4 class="mt-2 row">
                            <div class="col-md-6">
                            <button class="btn btn-link click-sub-account-group" data-toggle="collapse" data-target="#group-{{ $child->GroupId }}" aria-expanded="true" aria-controls="group-{{ $child->GroupId }}" data-id="{{ $child->GroupId }}">
                                <i class="fa fa-plus"></i> {{ $child->GroupTree }}. {{ $child->GroupName }}
                            </button>
                            </div>
                            <div class="col-md-6">
                            <button class="add-map-data btn btn-primary btn-sm float-left" onclick="mapAccount.showModalMapping({{ $child->GroupId }})">Map</button>
                            </div>
                        </h4>

                    </div>

                    <div id="group-{{ $child->GroupId }}" class="collapse" aria-labelledby="heading{{ $child->GroupId }}" data-parent="#accordion-{{ $parentId }}" data-check-open="{{ $child->GroupId }}">
                        <div class="card-body">
                            <div class="accordion-data-append-{{ $child->GroupId }}"></div>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        @endif
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".collapse").on('show.bs.collapse', function () {
            $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function () {
            $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
    });

</script>
