@extends('frontend.layouts.master')
@push('after-styles')

@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title">
                            Labelling
                        </h4>
                    </div>
                </div>
                <div class="iq-card-body">
                  <form action="{{ route('pharmacist.labelling.addlocallabels') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-5 col-md-6">

                            <div class="form-group form-row align-items-center er-input">
                                   <div class="custom-control custom-radio custom-control-inline">
                                      <input type="radio" id="customRadio6" name="label" value="essential" class="custom-control-input" checked>
                                      <label class="custom-control-label" for="customRadio6">Essential   </label>
                                  </div>
                                  <div class="custom-control custom-radio custom-control-inline">
                                      <input type="radio" id="customRadio7" name="label" value="frequency" class="custom-control-input">
                                      <label class="custom-control-label" for="customRadio7"> Frequency  </label>
                                  </div>
                                  <div class="custom-control custom-radio custom-control-inline">
                                      <input type="radio" id="customRadio8" name="label" value="dosage_form" class="custom-control-input">
                                      <label class="custom-control-label" for="customRadio8">Dosage Form</label>
                                  </div>
                            </div>
                            <div class="form-group form-row align-items-center er-input">
                                <label for="" class="col-sm-2">Word:</label>
                                      <div class="col-sm-10">
                                          <select name="fldengcode" id="wordselect" class="form-control" required>
                                          </select>
                                      </div>
                            </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                          <div class="form-group form-row align-items-center er-input">
                              <label for="" class="col-sm-3">English:</label>
                              <div class="col-sm-9">
                                  <input type="text" name="fldengdire" id="fldengdire" value="{{ old('fldengdire') }}" class="form-control">
                              </div>
                          </div>
                          <div class="form-group form-row align-items-center er-input">
                              <label for="" class="col-sm-3">local:</label>
                              <div class="col-sm-9">
                                  <input type="text" name="fldlocaldire" id="fldlocaldire" value="{{ old('fldlocaldire') }}" class="form-control">
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-3 col-md-12">
                          <div class="mb-1">
                             <button type="submit" class="btn btn-primary btn-action "><i class="fa fa-plus"></i>&nbsp; Save</button>
                          </div>
                          <a href="#" id="updateLabel" class="btn btn-primary btn-action " type="button"> <i class="ri-edit-fill"></i>&nbsp;Update</a>&nbsp;
                          <a href="{{ route('pharmacist.labelling.exportalllabellingtopdf') }}" target="_blank" class="btn btn-primary btn-action btn" type="button"><i class="fas fa-external-link-square-alt" title="Export all labelling to PDF"></i>&nbsp;Export</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
      </div>
 @include('pharmacist::layouts.includes.labellisting')
</div>
<form id="delete_form" method="POST">
    @csrf
    @method('delete')
</form>
</div>
@stop
@push('after-script')
<script>
    @php $selectedfldengcode = old('fldengcode');@endphp

    function optionForWord(value, selectedValue = null) {
        if(value == 'essential') {
            var options =   `<option></option>
                            <option value="Day" {{ ($selectedfldengcode == 'Day') ? 'selected' : '' }}>Day</option>
                            <option value="Difference" {{ ($selectedfldengcode == 'Difference') ? 'selected' : '' }}>Difference</option>
                            <option value="Evening" {{ ($selectedfldengcode == 'Evening') ? 'selected' : '' }}>Evening</option>
                            <option value="Every" {{ ($selectedfldengcode == 'Every') ? 'selected' : '' }}>Every</option>
                            <option value="Hour" {{ ($selectedfldengcode == 'Hour') ? 'selected' : '' }}>Hour</option>
                            <option value="Morning" {{ ($selectedfldengcode == 'Morning') ? 'selected' : '' }}>Morning</option>
                            <option value="Noon" {{ ($selectedfldengcode == 'Noon') ? 'selected' : '' }}>Noon</option>`;

            $('#wordselect').html(options);
            $.ajax({
                type: 'post',
                url : '{{ route('pharmacist.labelling.getByLabelType') }}',
                dataType: 'json',
                data: {
                    '_token' : '{{ csrf_token() }}',
                    'labeltype': "essential"
                },
                success : function(res) {
                    if(res.message == 'error'){
                        showAlert(res.error);
                    } else if(res.message == 'success') {
                        $("#labelListing").html(res.labelListing);
                    }

                }

            });
        } else if(value == 'frequency') {
            var options = `<option></option>
                            <option value="AM" {{ ($selectedfldengcode == 'AM') ? 'selected' : '' }}>AM</option>
                            <option value="Alt day" {{ ($selectedfldengcode == 'Alt day') ? 'selected' : '' }}>Alt day</option>
                            <option value="BID" {{ ($selectedfldengcode == 'BID') ? 'selected' : '' }}>BID</option>
                            <option value="Biweekly" {{ ($selectedfldengcode == 'Biweekly') ? 'selected' : '' }}>Biweekly</option>
                            <option value="HS" {{ ($selectedfldengcode == 'HS') ? 'selected' : '' }}>HS</option>
                            <option value="Hourly" {{ ($selectedfldengcode == 'Hourly') ? 'selected' : '' }}>Hourly</option>
                            <option value="Monthly" {{ ($selectedfldengcode == 'Monthly') ? 'selected' : '' }}>Monthly</option>
                            <option value="OD" {{ ($selectedfldengcode == 'OD') ? 'selected' : '' }}>OD</option>
                            <option value="PRN" {{ ($selectedfldengcode == 'PRN') ? 'selected' : '' }}>PRN</option>
                            <option value="Post" {{ ($selectedfldengcode == 'Post') ? 'selected' : '' }}>Post</option>
                            <option value="Pre" {{ ($selectedfldengcode == 'Pre') ? 'selected' : '' }}>Pre</option>
                            <option value="QID" {{ ($selectedfldengcode == 'QID') ? 'selected' : '' }}>QID</option>
                            <option value="SOS" {{ ($selectedfldengcode == 'SOS') ? 'selected' : '' }}>SOS</option>
                            <option value="TID" {{ ($selectedfldengcode == 'TID') ? 'selected' : '' }}>TID</option>
                            <option value="Tapering" {{ ($selectedfldengcode == 'Tapering') ? 'selected' : '' }}>Tapering</option>
                            <option value="Triweekly" {{ ($selectedfldengcode == 'Triweekly') ? 'selected' : '' }}>Triweekly</option>
                            <option value="Weekly" {{ ($selectedfldengcode == 'Weekly') ? 'selected' : '' }}>Weekly</option>
                            <option value="Yearly" {{ ($selectedfldengcode == 'Yearly') ? 'selected' : '' }}>Yearly</option>
                            <option value="stat" {{ ($selectedfldengcode == 'stat') ? 'selected' : '' }}>stat</option>`;
            $('#wordselect').html(options);
            $.ajax({
                type: 'post',
                url : '{{ route('pharmacist.labelling.getByLabelType') }}',
                dataType: 'json',
                data: {
                    '_token' : '{{ csrf_token() }}',
                    'labeltype': "frequency"
                },
                success : function(res) {
                    if(res.message == 'error'){
                        showAlert(res.error);
                    } else if(res.message == 'success') {
                        $("#labelListing").html(res.labelListing);
                    }

                }

            });
        } else if(value == 'dosage_form') {
            $.ajax({
                type: 'post',
                url : '{{ route('pharmacist.labelling.getvolunitmedbrand') }}',
                dataType: 'json',
                data: {
                    '_token' : '{{ csrf_token() }}',
                    'selectedfldendcode' : '{{ $selectedfldengcode }}',
                    'labeltype': "dosage_form"
                },
                success : function(res) {
                    if(res.message == 'error'){
                        showAlert(res.error);
                    } else if(res.message == 'success') {
                        $('#wordselect').html(res.html);
                        if(selectedValue != null){
                            $('#wordselect').val(selectedValue);
                        }
                        $("#labelListing").html(res.labelListing);
                    }

                }

            });
        }
        if(selectedValue != null){
            $('#wordselect').val(selectedValue);
        }
    }
    $(function() {
        var labelvalue = $('input[type=radio][name=label]').val();

        optionForWord(labelvalue);

        $(document).on("click","input[type=radio][name=label]",function() {
            var value = $(this).val();
            $('#fldengdire').val("");
            $('#fldlocaldire').val("");
            optionForWord(value);
        });

        // validation error message

        @if($errors->any())
            var validation_error = '';

            @foreach($errors->all() as $error)
                validation_error += '{{ $error }} \n';
            @endforeach

            showAlert(validation_error);
        @endif


        @if(Session::has('success_message'))
            var successmessage = '{{ Session::get('success_message') }}';
            showAlert(successmessage);
        @endif

        @if(Session::has('error_message'))
            var errormessage = '{{ Session::get('error_message') }}';
            showAlert(errormessage);
        @endif

        $(document).on('click','.deletelabel',function() {
            var really = confirm("You really want to delete this label?");
            var href = $(this).data('href');
            if(!really) {
                return false
            } else {
                $('#delete_form').attr('action', href);
                $('#delete_form').submit();
            }
        });
    });

    $(document).on('click','.editLabel',function(){
        var fldid = $(this).attr("data-fldid");
        $.ajax({
            url: baseUrl + '/pharmacist/labelling/editlocallabels/' + fldid,
            type: "GET",
            success: function (response) {
                if(response.success){
                    $("#updateLabel").attr("data-update-id",fldid);
                    $("#fldengdire").val(response.success.locallabel.fldengdire);
                    $("#fldlocaldire").val(response.success.locallabel.fldlocaldire);
                    if(response.success.locallabel.fldlabeltype != null){
                        $("input[type=radio][name=label][value="+response.success.locallabel.fldlabeltype+"]").prop("checked", true);
                    }
                    optionForWord(response.success.locallabel.fldlabeltype, response.success.locallabel.fldengcode);
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });

    $(document).on("click","#updateLabel",function(){
        var fldid = $(this).attr("data-update-id");
        var label = $("input[name='label']:checked").val();
        var fldengdire = $("#fldengdire").val();
        var fldlocaldire = $("#fldlocaldire").val();
        var wordselect = $("#wordselect").val();
        if(label != "" && fldlocaldire != "" && wordselect != ""){
            $.ajax({
                url: baseUrl + '/pharmacist/labelling/updatelocallabels/' + fldid,
                type: "POST",
                data: {
                    labeltype: label,
                    fldengcode: wordselect,
                    fldengdire: fldengdire,
                    fldlocaldire: fldlocaldire
                },
                success: function (response) {
                    if(response.success){
                        $("#labelListing").html(response.success.labelListing);
                        $('#fldengdire').val("");
                        $('#fldlocaldire').val("");
                        $('#wordselect').prop('selectedIndex',0);
                        showAlert("{{ __('messages.update', ['name' => 'Label']) }}");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }else{
            showAlert("An Error has occured!");
        }
    });
</script>
@endpush
