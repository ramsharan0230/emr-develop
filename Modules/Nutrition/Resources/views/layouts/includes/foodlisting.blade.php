
<div class="dietarytable">
    <div class="dietarytablefood overflow-auto container p-0" id="test-list">
        @php  $paginatedfoodlists = \App\Utils\Nutritionhelpers::getFoodlists(100) @endphp
        <!-- <input type="text" class="text search-input fuzzy-search" placeholder="Type here to search..."/> -->
        <ul class="list-group list" style="list-style-type: none;">
            @forelse($paginatedfoodlists as $k=>$foodlist)
            <li class="table-menu list-group-item list-group-medicine" type="button" data-toggle="collapse" data-target="#collapse_{{ $k }}" aria-expanded="false" aria-controls="collapseExample" >
                <i class="fas fa-angle-right"></i> {{ $foodlist->fldfood }} 
            </li>
            @php
            //  $foodcontents = Helpers::GetFldFoodId($foodlist->fldfood);
            $foodcontents = $foodlist->FoodContent;

            @endphp
            <div class="collapse" id="collapse_{{ $k }}" style="padding: 0px 25px;">
                <ul class="list-group" style="list-style-type: none;">
                    @forelse($foodcontents as $foodcontent)
                    <li class="table-menu list-group-item list-group-medicine">
                        <label for="">{{ $foodcontent->fldfoodid }}</label>
                        <a type="button" class="text-primary" href="{{ route('editfoodcontent', encrypt($foodcontent->fldfoodid)) }}" style="margin-left: 15px;" title="edit {{ $foodcontent->fldfoodid }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a type="button" title="delete {{ $foodcontent->fldfoodid }}" class="deletefood text-danger" data-href="{{ route('deletefoodcontent', encrypt($foodcontent->fldfoodid)) }}"><i class="far fa-trash-alt"></i></a>
                    </li>
                    @empty
                    @endforelse
                </ul>
            </div>

            @empty
            @endforelse
        </ul>
    </div>
</div>


<div class="form-group padding-none">
    <div class="form-inner">
        {{ $paginatedfoodlists->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
<script type="text/javascript">
   var monkeyList = new List('test-list', { 
      valueNames: ['name']
    });
</script>