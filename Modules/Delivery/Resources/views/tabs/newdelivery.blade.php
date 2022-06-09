<input type="hidden" name="delete_pat_findings" class="delete_pat_findings" value="{{ route('deletepatfinding') }}"/>
<div class="tab-pane fade show active" id="newdelivery" role="tabpanel" aria-labelledby="newdelivery-tab">
   <div class="row mt-4">
      <div class="col-sm-5">
       <div id="digo" class="collapse">
    </div>
    <div class="form-group-second form-row">
     <div class="custom-control custom-radio custom-control-inline" onclick="toggle_add_btn()">
        <input type="radio" name="newdelivery_type" id="type1" value="single" checked="checked" class="custom-control-input" />
        <label class="custom-control-label" for="type1"> Single </label>
    </div>&nbsp;
    <div class="custom-control custom-radio custom-control-inline" onclick="toggle_add_btn()">
        <input type="radio" id="type2" name="newdelivery_type" value="multiple" class="custom-control-input" />
        <label class="custom-control-label" for="type2"> Multiple </label>
    </div>
 </div>
    <div class="form-group-second form-row">
        <label for="" class="col-5">Consultant:</label>
        <div class="col-6">
            <select id="js-newdelivery-consultant-input" class="form-control select2">
                <option value="">-- Select --</option>
                @foreach($consultants as $consultant)
                <option value="{{ $consultant->username }}">{{ $consultant->fldtitlefullname }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-1">
           <button class="btn btn-sm-in btn-primary" type="button">
               <i class="fa fa-user"></i>
           </button>
       </div>
   </div>
   <div class="form-group-second form-row">
    <label for="" class="col-5">Nursing Staff:</label>
    <div class="col-sm-7">
        <select id="js-newdelivery-nursing-input" class="form-control select2" multiple>
            <option value="">-- Select --</option>
            @foreach($nurses as $nurse)
            <option value="{{ $nurse->username }}">{{ $nurse->fldfullname }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group-second form-row">
    <label for="" class="col-5">Delivery Time:</label>
    <div class="col-sm-7">
       <input type="text" placeholder="YYYY-MM-DD" class="form-control nepaliDatePicker" id="js-newdelivery-deliverydate-input" style="width: 60%; float: left;">
       <input type="text" placeholder="HH:MM" class="form-control" id="js-newdelivery-deliverytime-input" style="width: 37%;" autocomplete="off" >
   </div>
</div>
<div class="form-group-second form-row half_box">
    <label for="" class="col-5">Delivery Type:</label>
    <div class="col-2">
       <button data-variable="delivery_type" class="js-newdelivery-add-item btn btn-sm-in btn-primary" type="button">
            <i class="fa fa-plus"></i>
        </button>
    </div>
    <div class="col-5">
    <select id="js-newdelivery-deliverytype-input" class="form-control form-input-newdeli">
        <option value="">-- Select --</option>
        @foreach($delivered_types as $delivered_type)
        <option value="{{ $delivered_type->flditem }}">{{ $delivered_type->flditem }}</option>
        @endforeach
    </select>
</div>
</div>
<div class="form-group-second form-row">
    <label for="" class="col-5">Delivered Baby:</label>
    <div class="col-sm-7">
        <select id="js-newdelivery-deliverybaby-input" class="form-control">
            <option value="">-- Select --</option>
            @foreach($delivered_babies as $delivered_baby)
            <option value="{{ $delivered_baby }}">{{ $delivered_baby }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group-second form-row half_box">
    <label for="" class="col-5">Complication:</label>
    <div class="col-2">
       <button data-variable="complication" class="js-newdelivery-add-item btn btn-sm-in btn-primary" type="button">
            <i class="fa fa-plus"></i>
        </button>
    </div>
    <div class="col-5">
        <select id="js-newdelivery-complication-input" class="form-control form-input-newdeli">
            <option value="">-- Select --</option>
            @foreach($complications as $complication)
            <option value="{{ $complication->flditem }}">{{ $complication->flditem }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group-second form-row">
    <label for="" class=" col-sm-7 col-lg-5">WT of baby:</label>
    <div class="col-sm-2 col-lg-4 padding-none">
        <input type="text" value="" id="js-newdelivery-babyweight-input" class="form-control">
    </div>
    <div class="col-sm-3">
       <input type="text" class="form-control" placeholder="Gram" readonly />
   </div>
</div>
<div class="form-group-second form-row">
    <label for="" class=" col-sm-7 col-lg-5">WT of Placenta:</label>
    <div class="col-sm-2 col-lg-4 padding-none">
        <input type="text" value="" id="js-newdelivery-placenta-input" class="form-control">
    </div>
    <div class="col-sm-3">
       <input type="text" class="form-control" placeholder="Gram" readonly />
   </div>
</div>
<div class="form-group-second form-row">
    <label for="" class="col-5">Blood Loss(ml):</label>
    <div class="col-7 ">
       <input type="text" class="form-control" id="js-newdelivery-bloodloss-input"/>
   </div>
</div>
<div class="form-group-second form-row float-right">
    <button class="btn btn-primary" id="js-newdelivery-deliver-add-btn" type="button"><i class="fa fa-plus"></i>Add</button>&nbsp;
    <button class="btn btn-primary" id="js-newdelivery-deliver-update-btn" type="button"><i class="fa fa-edit"></i>Update</button>
</div>
</div>
<div class="col-sm-7">
    @include('delivery::diagnosis')

<div class="iq-card-header d-flex justify-content-between mt-4">
    <h5 class="card-title">Comment</h5>
</div>
<div class="form-group mb-0">
    <textarea class="form-control" name="newdelivery_detail" id="js-newdelivery-comment-input"></textarea>
</div>
</div>
</div>
<div class="res-table mt-3">
    <table class="table table-hovered table-bordered table-striped">
       <thead class="thead-light">
          <tr>
             <th>Datetime</th>
             <th>Delmode</th>
             <th>Delresult</th>
             <th>weight(Gram)</th>
             <th>Consultant</th>
             <th>Baby PatNo</th>
             <th>Baby Gender</th>
             <th>&nbsp;</th>
         </tr>
     </thead>
     <tbody id="js-newdelivery-tbody">
        @if(isset($deliveries))
        @foreach($deliveries as $delivery)
        <tr data-fldid="{{ $delivery->fldid }}">
            <td>{{ $delivery->flddeltime }}</td>
            <td>{{ $delivery->flddeltype }}</td>
            <td>{{ $delivery->flddelresult }}</td>
            <td>{{ $delivery->flddelwt }}</td>
            <td>{{ $delivery->flddelphysician }}</td>
            <td>{{ ($delivery->child) ? $delivery->child->fldpatientval : '' }}</td>
            <td>{{ ($delivery->child) ? $delivery->child->fldptsex : '' }}</td>
            <td><button class="btn btn-sm"><i class="fa fa-transgender"></i></button></td>
        </tr>
        @endforeach
        @endif
    </table>
</div>
</div>

@include('delivery::diagnosisstoremodal')
