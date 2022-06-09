@extends('frontend.layouts.master') 
@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 col-lg-12">
      <div class="iq-card"style="background-color: #ceebee;">
        <div class="iq-card-body">
         <form class="form-horizontal" style="background-color: #ceebee">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group form-row">
                <label class="col-lg-3 col-sm-4">Name:</label>
                <div class="col-lg-9 col-sm-8">
                  <label>Archana Rai</label>
                </div>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group form-row">
                <label class="col-lg-5 col-sm-7">Gender:</label>
                <div class="col-lg-7 col-sm-5">
                 <label>Female</label>
               </div>
             </div>
           </div>
           <div class="col-sm-3">
            <div class="form-group form-row">
              <label class="col-lg-4 col-sm-5">Religion:</label>
              <div class="col-lg-8 col-sm-7">
               <label>Buddhist</label>
             </div>
           </div>
         </div>
         <div class="col-sm-2">
          <div class="form-group form-row">
            <label class="col-lg-3 col-sm-4">MRN:</label>
            <div class="col-lg-9 col-sm-8">
             <label>123456758</label>
           </div>
         </div>
       </div>
       <div class="col-sm-3 col-lg-2 pr-0">
        <label>Age:23yr|07-apr-1997</label>
      </div>

      <div class="col-sm-3">
        <div class="form-group form-row">
          <label class="col-lg-4 col-sm-6">Nationality:</label>
          <div class="col-lg-8 col-sm-6">
           <label>Nepali</label>
         </div>
       </div>
     </div>
     <div class="col-sm-2">
      <div class="form-group form-row">
        <label class="col-lg-7 col-sm-10">Blood-Grp:</label>
        <div class="col-lg-5 col-sm-2">
         <label>A+</label>
       </div>
     </div>
   </div>
   <div class="col-sm-3">
    <div class="form-group form-row">
      <label class="col-lg-4 col-sm-6">Mobile no:</label>
      <div class="col-lg-8 col-sm-6">
       <label>123456758</label>
     </div>
   </div>
 </div>
 <div class="col-sm-4">
  <div class="form-group form-row">
    <label class="col-lg-4 col-sm-6">Nationality no:</label>
    <div class="col-lg-8 col-sm-6">
     <label>123456758</label>
   </div>
 </div>
</div>
</div>
</form>
</div>
</div>
</div>
<div class="col-sm-12">
  <div class="row">
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/appointment-icon.jpg) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Apponitment</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/priscription.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Priscribtions</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/lab.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Laboratory Result</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/radiology.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Radiology & Imaging Result</h6>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-12">
  <div class="row">
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/icon-consulting.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Consultation Notes</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/immunization.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Immunizations Schedule</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/discharge.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Discharge Summary</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/history.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Medical History</h6>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-12">
  <div class="row">
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/chat.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Chat with Doctor</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/Video.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Video Conference</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/bill.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Bills & Payments</h6>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <div class="iq-card-body P-0 rounded img-dash" style="background: url(new/images/document.png) no-repeat scroll center center; background-size: contain;"></div>
          <h6 class="mb-2 mt-2 text-center">Add Documents</h6>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
