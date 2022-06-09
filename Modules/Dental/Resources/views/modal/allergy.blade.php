
<div id="allergy-dental" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card-header d-flex ">
        <div class="iq-header-title d-flex align-items-center">
            <h4 class="card-title">Allergy</h4>
            <div class="custom-control custom-checkbox ml-4">
                <input type="checkbox" class="custom-control-input">
                <label class="custom-control-label">All Enc</label>
            </div>
        </div>
        <div class="allergy-add ml-4">
            @if((isset($enable_freetext) && $enable_freetext == '1') || (isset($enable_freetext) && $enable_freetext  == 'Yes'))
            <a href="#" class="iq-bg-primary" data-toggle="modal" data-target="#allergyfreetext" onclick="allergyfreetext.displayModal()"><i class="ri-add-fill"></i></a>
            @else
            <a href="#" class="iq-bg-secondary"><i class="ri-add-fill"></i></a>
            @endif
            <a href="#" class="iq-bg-primary" data-toggle="modal" data-target="#allergicdrugs"><i class="ri-add-fill"></i></a>
            <!-- <a href="#" class="iq-bg-secondary"><i class="ri-add-fill"></i></a> -->
            <a href="javascript:void(0)" class="iq-bg-danger" id="deletealdrug"><i class="ri-delete-bin-5-fill"></i></a>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="delete_pat_findings" class="delete_pat_findings" value="{{ route('deletepatfinding') }}"/>
        <select name="" id="select-multiple-aldrug" multiple class="form-control">
            @if(isset($patdrug) && count($patdrug) >0)
            @foreach($patdrug as $pd)
            <option value="{{$pd->fldid}}">{{$pd->fldcode}}</option>
            @endforeach
            @else
            <option value="">No Allergic Drugs Found</option>
            @endif
        </select>
    </div>
</div>

<!-- <div class="collapse" id="allergy">
    <div class="mt-3">
        <div class="form-group">
            <div class="form-group-inner custom-6">
                <label for="" class="form-label">Allergy </label>
                <input type="checkbox" name="" id="enc" class="form-input">
                <label for="enc" class="chekbox-label">All Enc</label>
                <a href="#" id="deletealdrug" class="{{ $disableClass }}"><img src="{{asset('assets/images/delete.png')}}" alt=""></a>
                @if(isset($enable_freetext) and $enable_freetext == 1)
                    <a href="#" class="{{ $disableClass }}" data-toggle="modal" data-target="#allergyfreetext" onclick="allergyfreetext.displayModal()"><img src="{{asset('assets/images/add.png')}}" alt=""></a>
                @else
                    <img src="{{asset('assets/images/add-gray.png')}}" alt="">
                @endif

                <a href="#" class="{{ $disableClass }}" data-toggle="modal" data-target="#allergicdrugs"><img src="{{asset('assets/images/add.png')}}" alt=""></a>
            </div>
        </div>
        <div class="form-group">
            <div class="form-group-inner custom-9">
                <input type="hidden" name="delete_pat_findings" class="delete_pat_findings" value="{{ route('deletepatfinding') }}"/>
                <select name="" id="select-multiple-aldrug" class="form-input" multiple>
                    @if(isset($patdrug) && count($patdrug) >0)
                        @foreach($patdrug as $pd)
                            <option value="{{$pd->fldid}}">{{$pd->fldcode}} <a class="right_del" href="{{route('deletepatfinding',$pd->fldid)}}" onclick="return confirm('Are you sure you want to delete this Allergic Drug?');"><i class="fas fa-trash-alt"></i></a></option>
                        @endforeach
                    @else
                        <option value="">No Allergic Drugs Found</option>
                    @endif
                </select>
            </div>
        </div>
    </div>
</div> -->



        @include('outpatient::modal.allergy-freetext-modal')

        @push('after-script')
        <script type="text/javascript">
            var allergy = {
                displayModal: function (encId) {
                    $('#js-allergy-modal').modal('show');
                },
            }
        </script>

@endpush
