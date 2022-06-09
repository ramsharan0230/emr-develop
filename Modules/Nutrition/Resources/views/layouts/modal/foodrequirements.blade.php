
<!-- The Modal -->
<div class="modal" id="food_requirements">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color: #333232 !important;">
                <h6 class="modal-title">Nutritional Requirement</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Age Group:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 77%;">
                                <select class="form-input" name="fldagegroup" id="fldagegroup">
                                    <option value=""></option>
                                    <option value="Adolescent" selected=selected>Adolescent</option>
                                    <option value="Adult" selected=selected>Adult</option>
                                    <option value="All age" selected=selected>All Age</option>
                                    <option value="Children" selected=selected>Children</option>
                                    <option value="Elderly" selected=selected>Elderly</option>
                                    <option value="Infant" selected=selected>Infant</option>
                                    <option value="Neonate" selected=selected>Neonate</option>
                                    <option value="Toddler" selected=selected>Toddler</option>
                                </select>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Protein:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" step="any" min="0" name="fldprotein" id="fldprotein" value="" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">mg/kg</label>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Lipid:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" step="any" min="0"  name="fldlipid" id="fldlipid" value="" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">mg/kg</label>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Dextrose:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" step="any" min="0" name="flddextrose" id="flddextrose" value="" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">mg/kg</label>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Non-N Energy:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" step="any" min="0" name="fldnne" id="fldnne" value="" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">kcal/kg</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Gender:</label>
                            </div>&nbsp;
                            <div class="box__input" style="flex: 0 0 67%;">
                                <select class="form-input-nutri" name="fldptsex" id="fldptsex">
                                    <option value=""></option>
                                    <option value="Both Sex" selected=selected>Both Sex</option>
                                    <option value="Female" selected=selected>Female</option>
                                    <option value="Male" selected=selected>Male</option>
                                </select>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Fluid:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" step="any" min="0" value="" name="fldfluid" id="fldfluid" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">ml/kg</label>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Sodium:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" name="fldsodium" id="fldsodium" step="any" min="0" value="" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">mEq/kg</label>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Pottasium:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" name="fldpotassium" id="fldpotassium" step="any" min="0" value="" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">mEq/kg</label>
                            </div>
                        </div>
                        <!-- next_group -->
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 28%;">
                                <label class="border-none" style="width:100%;">Vitamin:</label>
                            </div>&nbsp;
                            <div class="input-nutri">
                                <input type="number" name="fldvitamin" id="fldvitamin" step="any" min="0" value="" class="input-nutri" placeholder="0">
                            </div>&nbsp;
                            <div class="box__label">
                                <label class="label-width-third">ml/kg</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 15%;">
                                <label class="border-none" style="width:100%;">Refrence:</label>
                            </div>&nbsp;
                            <div class="box__input">
                                <input type="text" name="fldreference" id="fldreference" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="group__box half_box">
                            <div class="box__label" style="flex: 0 0 18%;">
                                <button type="button" class="default-btn f-btn-icon-b" id="addfoodrequirement"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                            </div>
                            <div class="box__label">
                                <button type="button" class="default-btn" data-dismiss="modal"><img src="{{asset('assets/images/edit.png')}}" width="16px">&nbsp;&nbsp;Edit</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="group__box half_box">
                            <div class="box__label" style="margin-right: -55px;">
                                <button type="button" class="default-btn" data-dismiss="modal"><i class="fas fa-play"></i></button>
                            </div>
                            <div class="box__label">
                                <button type="button" class="default-btn" data-dismiss="modal"><i class="fas fa-play fa-rotate-180"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="group__box half_box">
                            <div class="box__input" style="flex: 0 0 69%;">
                                <button type="button" class="default-btn f-btn-icon-s" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;&nbsp;Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){

        $('#addfoodrequirement').click(function() {
            var fldagegroup = $('#fldagegroup').val();
            var fldptsex = $('#fldptsex').val();
            var fldfluid = $('#fldfluid').val();
            var fldprotein = $('#fldprotein').val();
            var fldlipid = $('#fldlipid').val();
            var flddextrose = $('#flddextrose').val();
            var fldnne = $('#fldnne').val();
            var fldsodium = $('#fldsodium').val();
            var fldpotassium = $('#fldpotassium').val();
            var fldvitamin = $('#fldvitamin').val();
            var fldreference = $('#fldreference').val();

            $.ajax({
               'type' : 'post',
               'url' : '{{ route('addnutrition') }}',
               'data' : {
                     '_token' : '{{ csrf_token() }}',
                     'fldagegroup' : fldagegroup,
                     'fldptsex' : fldptsex,
                     'fldfluid' : fldfluid,
                     'fldprotein' : fldprotein,
                     'fldlipid' : fldlipid,
                     'flddextrose' : flddextrose,
                     'fldnne' : fldnne,
                     'fldsodium' : fldsodium,
                     'fldpotassium' : fldpotassium,
                     'fldvitamin' : fldvitamin,
                     'fldreference' : fldreference
               },
                'success' : function(res) {

                    showAlert(res);

                     $('#fldagegroup').val('');
                     $('#fldptsex').val('');
                     $('#fldfluid').val('');
                     $('#fldprotein').val('');
                     $('#fldlipid').val('');
                     $('#flddextrose').val('');
                     $('#fldnne').val('');
                     $('#fldsodium').val('');
                     $('#fldpotassium').val('');
                     $('#fldvitamin').val('');
                     $('#fldreference').val('');
                }
             });
        })
    });
</script>
<!-- The Modal -->
