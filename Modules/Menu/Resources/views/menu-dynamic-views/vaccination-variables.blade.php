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
</style>
<form action="" class="vaccinationVariableAdd">
    @csrf
    <div class="container-fluid">
        <div class="form-row">
            <div class="form-group col-md-8">
                <input type="text" class="vaccination_variable" name="vaccination_variable" placeholder="Vaccination Variable">
            </div>
            <div class="form-group col-md-3">
                <a href="javascript:;" onclick="VaccinationVariables.addVariable()">Add</a>
            </div>

        </div>
        <table class="table table-sm scrollbar">
            <thead>
            <tr>
                <th>Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="variableListing">
            @if(count($vaccineList))
                @foreach($vaccineList as $list)
                    <tr>
                        <td>{{ $list->flditem }}</td>
                        <td><a href="javascript:;" onclick="VaccinationVariables.deleteVariable('{{ $list->fldid }}')"><img src="{{ asset('assets/images/cancel.png') }}" alt=""></a></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</form>
<script>
    var VaccinationVariables = {
        addVariable: function () {
            if ($(".vaccination_variable").val() !== '') {
                $.ajax({
                    url: "{{ route('patient.vaccination.variable.add') }}",
                    type: "POST",
                    data: $('.vaccinationVariableAdd').serialize(),
                    success: function (data) {
                        // console.log(data);
                        if (data === "Data Exists"){
                            alert('Already contains data');
                            return false;
                        }

                        $('.variableListing').empty();
                        $('.variableListing').html(data.tableData);
                        $('.vaccination_name_main_form').empty();
                        $('.vaccination_name_main_form').html(data.selectData);
                    },
                    error: function (xhr, err) {
                        console.log(xhr);
                    }
                });
            } else {
                alert('Variable must not be empty')
            }

        },
        deleteVariable: function (fldid) {
            var confirmDelete = confirm('Delete?');
            if (confirmDelete == false) {
                return false;
            }

            $.ajax({
                url: "{{ route('patient.vaccination.variable.delete') }}",
                type: "POST",
                data: {fldid:fldid},
                success: function (data) {
                    $('.variableListing').empty();
                    $('.variableListing').html(data.tableData);
                    $('.vaccination_name_main_form').empty();
                    $('.vaccination_name_main_form').html(data.selectData);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }
    }
</script>
