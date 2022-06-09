<div class="col-sm-12">
 <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
  <div class="iq-card-header d-flex justify-content-between">
   <div class="iq-header-title">
    <h4 class="card-title">Chief Complaints</h4>
  </div>
</div>
<div class="iq-card-body">
 <div class="cheif-complaints">

  <div class="form-group form-row align-items-center">
   <div class="col-sm-3">
    <select name="flditem" class="form-control flditem">

     @if(isset($complaint))
     @foreach($complaint as $com)
     <option value="{{ $com->fldsymptom }}">{{ $com->fldsymptom }}</option>
     @endforeach
     @endif
   </select>
 </div>
 <div class="col-sm-1">
  <input name="duration" class="form-control duration remove_zero_to_empty" type="numeric" value="0" min="0" />

</div>
<div class="col-sm-2">
  <select name="duration_type" class="select-2 form-control duration_type">
   <option value="">--Select--</option>
   <option value="Hours">Hours</option>
   <option value="Days">Days</option>
   <option value="Weeks">Weeks</option>
   <option value="Months">Months</option>
   <option value="Years">Years</option>
 </select>
</div>
<div class="col-sm-2">
  <select name="fldreportquali" class="select-3 form-control fldreportquali">
   <option value="">--Select--</option>
   <option value="Left Side">Left Side</option>
   <option value="Right Side">Right Side</option>
   <option value="Both Side">Both Side</option>
   <option value="Episodes">Episodes</option>
   <option value="On/Off">On/Off</option>
   <option value="Present">Present</option>
 </select>
</div>
<div class="col-sm-1">

  <button class="btn btn-sm-in btn-primary" id="insert_complaints_emergency" url="{{ route('insert_complaint_emergency')}}"><i class="fa fa-plus"></i></button>
</div>
</div>

<div class="res-table">
 <table class="table table-hovered table-bordered table-striped">
  <thead class="thead-light">
   <tr>
    <th>&nbsp;</th>
    <th>Symptoms</th>
    <th>Dura</th>
    <th>Side</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>Time</th>
    <th>User</th>
       <th>Detail</th>
  </tr>
</thead>
<tbody class="get_cheif_complent_data_table">
 @if(isset($examgeneral))
 @foreach($examgeneral as $general)
 <tr id="com_{{ $general->fldid }}">
  <td></td>
  <td>{{ $general->flditem }}</td>
  <td>
   @if($general->fldreportquanti <= 24) {{ $general->fldreportquanti }} hr @endif @if($general->fldreportquanti > 24 && $general->fldreportquanti <=720 ) {{ round($general->fldreportquanti/24,2) }} Days @endif @if($general->fldreportquanti > 720 && $general->fldreportquanti <8760) {{ round($general->fldreportquanti/720,2) }} Months @endif @if($general->fldreportquanti >= 8760) {{ round($general->fldreportquanti/8760) }} Years @endif
 </td>
 <td>{{ $general->fldreportquali }}</td>
 <td><a href="javascript:;" permit_user="{{ $general->flduserid }}" class="delete_complaints {{ $disableClass }} text-danger" url="{{ route('delete_complaint_emergency',$general->fldid) }}"><i class="ri-delete-bin-5-fill"></i></a></td>
 <td><a href="javascript:;" permit_user="{{ $general->flduserid }}" data-toggle="modal" data-target="#edit_complaint_emergency" old_complaint_detail="{{$general->flddetail}}" class="clicked_edit_complaint {{ $disableClass }} text-primary" clicked_flag_val="{{ $general->fldid }}">
  <i class="ri-edit-2-fill"></i></a></td>
  <td>{{ $general->fldtime }}</td>
  <td>{{ strip_tags($general->flduserid) }}</td>
  <td>{{ strip_tags($general->flddetail) }}</td>
</tr>
@endforeach
@endif
</tbody>

</table>
</div>
</div>
</div>
</div>
</div>

<!-- Edit Complaint Modal -->
<div class="modal fade" id="edit_complaint_emergency" tabindex="-1" role="dialog" aria-labelledby="edit_complaintLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <input type="hidden" id="complaintfldid" name="fldid" value="">
      <div class="modal-header">
        <h5 class="modal-title" id="edit_complaintLabel" style="text-align: center;">Edit Complaint</h5>
        <button type="button" class="close onclose" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <textarea name="flddetail" class="flddetail_complaint" id="editor_emergency"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
        <button id="submit_detail_complaint" class="btn btn-primary" url="{{ route('insert_complaint_detail_emergency') }}">Save changes</button>
      </div>
    </div>
  </div>
</div>
