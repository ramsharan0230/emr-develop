<style>
    .scrollbar {
        background-color: #F5F5F5;
        float: left;
        height: 300px;
        margin-bottom: 25px;
        margin-top: 40px;
        /*width: 35%;*/
        overflow-y: scroll;
    }
    .modal-select-data {
        width: 42% !important;
    }

</style>
<form id="vaccination-complete-submit" class="vaccination-form container" method="post">
    @csrf
    <input type="hidden" name="encounter" value="{{ $encounterId }}" id="encounter">

    <div class="form-group align-items-center form-row">
        <label for="name" class="col-sm-2">Name</label>
        <div class="col-md-8">
            <select name="name_vaccination" class="form-control" id="name_vaccination" required>
                <option value="">Select</option>
                @if(count($vaccineList))
                    @foreach($vaccineList as $list)
                        <option value="{{ $list->flditem }}">{{ $list->flditem }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-2">
            <a href="javascript:;" class="btn btn-primary" onclick="vaccinationInner.addName()"><i class="ri-add-line"></i> Add</a>
        </div>
    </div>

    <div class="form-group align-items-center form-row">
        <label for="vaccination_schedule_label" class="col-sm-2">Schedule</label>
        <div class="col-md-10">
            <select name="vaccination_schedule" id="vaccination_schedule" class="form-control" required>
                <option value="">Select</option>
                <option value="First Dose">First Dose</option>
                <option value="Second Dose">Second Dose</option>
                <option value="Third Dose">Third Dose</option>
                <option value="Fourth Dose">Fourth Dose</option>
                <option value="Fifth Dose">Fifth Dose</option>
            </select>
        </div>
    </div>

    <div class="form-row form-group align-items-center">
        <label for="vaccination_schedule_label" class="col-sm-2">Dose</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="vaccination_dose" id="vaccination_dose" value="0">
        </div>&nbsp;
        <div class="col-sm-4">
            <input type="text" class="form-control" name="vaccination_unit" id="vaccination_unit">
        </div>&nbsp;
        <div class="col-sm-2">
            <a href="javascript:;" class="btn btn-primary" onclick="vaccinationInner.addVaccination()"><i class="ri-add-line"></i> Add</a>
        </div>
    </div>
</form>
    <div class="res-table">
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th>DateTime</th>
                    <th>Vaccine</th>
                    <th>Schedule</th>
                    <th>Dose</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="vaccination_list_body">
            @if(count($vaccDosing))
                @foreach($vaccDosing as $dosList)
                    <tr id="vaccine_id">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dosList->fldtime }}</td>
                        <td>{{ $dosList->flditem }}</td>
                        <td>{{ $dosList->fldtype }}</td>
                        <td>{{ $dosList->fldvalue }}</td>
                        <td>{{ $dosList->fldunit }}</td>
                        <td><a href="javascript:;" class="btn btn-default" onclick="updateVaccineDosing({{$dosList->fldid}})"><img src="{{ asset('assets/images/edit.png') }}" alt="Update" style="width: 15px;"></a></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>


{{--<button type="button" class="btn btn-secondary btn-sm onclose" data-dismiss="modal">Close</button>--}}

<script>
    var vaccinationInner = {
        addName: function () {
            $.ajax({
                url: "{{ route('patient.vaccination.variable.form') }}",
                type: "POST",
                success: function (data) {
                    // console.log(data);

                    $('.general-modal-title').empty();
                    $('.general-form-data').empty();
                    $('.general-modal-title').text('Variables');
                    $('.general-form-data').html(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });

            $('#general-modal').modal({show: true});
        },
        addVaccination: function () {
            /*if ($('.vaccination_name_main_form').val() === "" || $('.vaccination_schedule').val() === "" || $('.vaccination_dose').val() === "") {
                alert('Must contain all data');
                return false;
            }*/
            $.ajax({
                url: "{{ route('patient.vaccination.add') }}",
                type: "POST",
                data: $('.vaccination-form').serialize(),
                success: function (data) {
                    console.log(data);
                    if(data.message === "The given data was invalid."){
                        alert('Fields empty')
                    }
                    $('.vaccination_list_body').empty();
                    $('.vaccination_list_body').html(data);
                    $('.vaccination-form')[0].reset();
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }
    }
    function updateVaccineDosing(val){
        if(confirm('Are You Sure ?')){
            $.ajax({
                url: "{{ route('patient.vaccination.edit') }}",
                type: "POST",
                data: {val:val,encounter:$("#encounter").val(),name_vaccination:$("#name_vaccination").val(),vaccination_schedule:$("#vaccination_schedule").val(),vaccination_dose:$("#vaccination_dose").val(),vaccination_unit:$("#vaccination_unit").val()},
                success: function (data) {
                    console.log(data);
                    if(data.message === "The given data was invalid."){
                        alert('Fields empty')
                    }
                    $('.vaccination_list_body').empty();
                    $('.vaccination_list_body').html(data);
                    $('.vaccination-form')[0].reset();
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }else{

            return false;
        }
    }
</script>
<style>
    .vaccination-form .form-group a img {
        width: 15px;
    }
</style>
