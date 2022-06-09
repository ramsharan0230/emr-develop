

<form action="{{ route('patient.laboratory.form.save.waiting') }}"  class="laboratory-form" method="post">
    @csrf
    @php
        $encounterData = $encounter[0];
        $encounterDataPatientInfo = $encounter[0]->patientInfo;
    @endphp
    <input type="hidden" name="encounter" value="{{ $encounterId }}">
    <input type="hidden" name="fldinput" value="Obstetric">
    <div class="modal-body">
        <div class="head-modal">
            <input type="checkbox"  class="form-input " id="selected_investigation" value=""> Selected Investigation
        </div>
        <div class="laboratory-modal-content">
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Course of Treatment
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group">
                <input type="checkbox" name="" class="form-input"> Select ALL
            </div>
            <div class="modal-btn">
                <button type="submit">Save</button>
            </div>  
        </div>
    </div>
        
    </div>
</form>


<script type="text/javascript">
    /*jQuery(document).ready(function ($) {
        $('#multiselect').multiSelect();
    });*/
    var insertUpdateRequest = {
        insertRequest: function () {
            $.ajax({
                url: $('#laboratory-request-submit').attr('action'),
                type: $('#laboratory-request-submit').attr('method'),
                data: $('#laboratory-request-submit').serialize(),
                success: function (data) {
                    // alert('Submitted');
                    // console.log(data)
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
            return false;
        },
        updateRequest: function () {
            $.ajax({
                url: "{{ route('patient.laboratory.form.save.done') }}",
                type: "POST",
                data: $('#laboratory-request-submit').serialize(),
                success: function (data) {
                    // console.log(data);
                    $('#pending-list-lab').empty();
                    $('#pending-list-lab').append(data);
                    $('#patbillingData').empty();
                    $('#patbillingData').append(data);
                },
                error: function (xhr, err) {
                    console.log(xhr);
                }
            });
        }
    };

    $(document).ready(function () {
        $('#save-request-waiting').on('click', function (e) {
            e.preventDefault();
            insertUpdateRequest.insertRequest();
        });

        $('#save-request').on('click', function (e) {
            e.preventDefault();
            insertUpdateRequest.updateRequest();
        });
    });
</script>
