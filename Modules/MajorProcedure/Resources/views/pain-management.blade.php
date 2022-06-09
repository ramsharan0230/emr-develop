<style type="text/css">
  .scroll-pain{
    max-height: 150px;
    overflow: scroll;
  }
</style>

@extends('frontend.layouts.master')
@section('content')
<div class="container-fluid">
   <div class="row">
        @include('frontend.common.patientProfile')
        
          <div class="col-sm-12">
             <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                  <form id="pain-form">
                    <hr class="mt-0">
                   <div class="row">
                      <div class="col-sm-6">
                         <div class="form-group">
                            <label>Digonosis:</label>
                               <select name="" id="" class="form-control" multiple>
                                  @if(isset($intradiagnosis) && count($intradiagnosis) >0)
                                      @foreach($intradiagnosis as $id)
                                          <option value="{{$id->fldid}}">{{$id->fldcode}} </option>
                                      @endforeach
                                  @else
                                      <option value="">No Diagnosis Found</option>
                                  @endif
                              </select>
                         </div>
                      </div>
                      <div class="col-sm-6">
                         <div class="form-group">
                            <label>Comorbidities: </label>
                               <textarea class="form-control scroll-pain" rows="10" id="comorbidities">{{ isset($comorbidities) ? $comorbidities->fldvalue : ''}}</textarea>
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                         <div class="form-group">
                            <label>Pain Score:</label>
                              <input type="text" name="pain_score" value="{{ isset($paindata['otherData']['pain_score']) ? $paindata['otherData']['pain_score'] : ''}}" id="pain_score" class="form-control">
                         </div>
                      </div>
                      <div class="col-sm-6">
                         <div class="form-group">
                            <label>Follow Up: </label>
                               <input type="date" name="follow_up_date" class="form-control" id="exampleInputdate" value="{{ isset($paindata['otherData']['follow_up_date']) ? $paindata['otherData']['follow_up_date'] : ''}}">
                         </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-sm-6">
                         <div class="form-group">
                            <label>Pain Management:</label><br/>
                              <input type="checkbox" name="pain_management[]" value="Counselling"  class="custom-checkbox" {{(isset($painmanagement) and in_array('Counselling',$painmanagement)) ? 'checked':''}}>Counselling<br/>
                              <input type="checkbox" name="pain_management[]" value="Drugs"  class="custom-checkbox" {{(isset($painmanagement) and in_array('Drugs',$painmanagement)) ? 'checked':''}}>Drugs<br/>
                              <input type="checkbox" name="pain_management[]" value="Intervention"  class="custom-checkbox" {{(isset($painmanagement) and in_array('Intervention',$painmanagement)) ? 'checked':''}}>Intervention<br/>
                         </div>
                      </div>
                      
                   </div>
                   <input type="hidden" name="fldencounterval" id="fldencounterval" value="@if(isset($enpatient)){{ $enpatient->fldencounterval }}@endif "/>
                  </form>
                </div>
             </div>
          </div>
          
          
        
      <div class="col-sm-12">
            <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-body">
                    <div class="justify-content-around">
                        <a href="javascript:void(0);"  class="mr-4 btn btn-primary" onclick="savePainDetail()">
                                Save
                            </a>
                         
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>
<script type="text/javascript">
  CKEDITOR.replace('js-modality',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-intervention',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-drug',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-counselling',
    {
    height: '100px',
    } );
  CKEDITOR.replace('js-followup',
    {
    height: '100px',
    } );
  CKEDITOR.replace('comorbidities',
        {
        height: '200px',
        } );
function savePainDetail(){
        // alert('saveanaesthesia');
        var url = "{{route('savePaindetail')}}";
        var alldata = $("#pain-form").serialize();
        // alert(alldata);
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url: url,
            type: "POST",
            data:  $("#pain-form").serialize(),"_token": "{{ csrf_token() }}",
            success: function(response) {
                // response.log()
                // console.log(response);
                // $('#select-multiple-diagno').html(response);
                // $('#diagnosis').modal('hide');
                showAlert('Information Saved !!');
                // if ($.isEmptyObject(data.error)) {
                //     showAlert('Data Added !!');
                //     $('#allergy-freetext-modal').modal('hide');
                // } else
                //     showAlert('Something went wrong!!');
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }
</script>
@endsection

