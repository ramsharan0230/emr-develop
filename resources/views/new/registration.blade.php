 @extends('frontend.layouts.master')
 @section('content')
 <div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
          <button class="accordion accordion-box">New Patient<i class="fa fa-down float-right"></i></button>
          <div class="panel mt-3 mb-3">
            <div class="form-row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Patient No.</label>
                  <div class="form-row">
                    <div class="col-sm-5">
                      <input type="text" name="" id="" class="form-control">
                    </div>
                    <div class="col-sm-5">
                      <input type="text" name="" id="" class="form-control">
                    </div>
                    <div class="col-sm-1">
                      <button type="button" class="btn btn-primary"><i class="ri-refresh-line"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Encounter Id</label>
                  <input type="text" name="" id="" placeholder="File No" class="form-control">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Billing Mode</label>
                  <select name="" id="" class="form-control">
                    <option value="0">---select---</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">NHSI No.</label>
                  <input type="text" name="" id="" placeholder="NHSI No." class="form-control">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Discount No.</label>
                  <input type="text" name="" id="" placeholder="Discount No" class="form-control">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="">Consult</label>
                  <div class="form-row">
                    <div class="col-sm-2 text-center">
                      <input type="checkbox" name="" id="">
                    </div>
                    <div class="col-sm-8">
                      <input type="text" name="" id="" class="form-control">
                    </div>
                    <div class="col-sm-2">
                      <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button class="accordion accordion-box">Consultant<i class="fa fa-down float-right"></i></button>
          <div class="panel mt-3 mb-3">
            <div class="form-row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Consultant</label>
                  <input type="text" name="" id="" placeholder="Consultant" class="form-control">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Date Time</label>
                  <input type="Date" name="" id="" placeholder="Date Time" class="form-control">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">last visit</label>
                  <input type="text" name="" id="" placeholder="last visit" class="form-control">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">Followup</label>
                  <input type="text" name="" id="" placeholder="Followup" class="form-control">
                </div>
              </div>
            </div>
          </div>

          <button class="accordion accordion-box">Personal Information<i class="fa fa-down float-right"></i></button>
          <div class="panel mt-3 mb-3">
           <div class="form-row">
            <div class="col-sm-8">
              <div class="form-group">
                <label for="">First Name <span class="text-danger">*</span></label>
                <input type="text" name="" id="" placeholder="First Name" class="form-control">
              </div>
              <div class="form-group form-row">
                <div class="col-sm-6">
                  <label for="">Middle Name</label>
                  <input type="text" name="" id="" placeholder="Middle Name" class="form-control">
                </div>
                <div class="col-sm-6">
                 <label for="">Last Name</label>
                 <input type="text" name="" id="" placeholder="Last Name" class="form-control">
               </div>
             </div>
             <div class="form-group form-row">
              <div class="col-sm-6">
               <label for="">Gender</label>
               <select name="" id="" class="form-control">
                <option value="0">---select---</option>
              </select>
            </div>
            <div class="col-sm-6">
             <label for="">Contact Number</label>
             <input type="text" name="" id="" placeholder="Contact Number" class="form-control">
           </div>
         </div>
         <div class="form-group form-row">
          <div class="col-sm-6">
           <label for="">Email</label>
           <input type="email" name="" id="" placeholder="Email" class="form-control">
         </div>
         <div class="col-sm-6">
           <label for="">Date of Birth</label>
           <div class="form-row">
            <div class="col-sm-7">
             <input type="date" name="" id="" placeholder="Date" class="form-control">
           </div>
           <div class="col-sm-2">
            <label for="">Age</label>
          </div>
          <div class="col-sm-3">
            <input type="text" name="" id="" value="0" class="form-control">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group mt-5">
      <img id="profile" class="img-info" src="{{ asset('assets/images/dummy-img.jpg')}}" alt="your image" style="width: 47%; margin-left: 32%;" />
    </div>
    <div class="form-group text-right">
      <input type='file' class="col-9" onchange="readURL(this);" />
    </div>
  </div>
  </div>
</div>
</div>
<button class="accordion accordion-box">Other information<i class="fa fa-down float-right"></i></button>
<div class="panel mt-3 mb-3">
  <div class="form-row">
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">District</label>
        <select name="" id="" class="form-control">
          <option value="0">---select---</option>
        </select>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Municipality</label>
        <select name="" id="" class="form-control">
          <option value="0">---select---</option>
        </select>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Ward No.</label>
        <select name="" id="" class="form-control">
          <option value="0">---select---</option>
        </select>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Province</label>
        <select name="" id="" class="form-control">
          <option value="0">---select---</option>
        </select>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Country</label>
        <select name="" id="" class="form-control">
          <option value="0">---select---</option>
        </select>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">National Id</label>
        <input type="text" name="" id="" placeholder="National Id" class="form-control">
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Guardian</label>
        <input type="text" name="" id="" placeholder="Guardian" class="form-control">
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Relation</label>
        <input type="text" name="" id="" placeholder="Relation" class="form-control">
      </div>
    </div>
  </div>
</div>
<button class="accordion accordion-box">Additional information<i class="fa fa-down float-right"></i></button>
<div class="panel mt-3 mb-3">
  <div class="form-row">
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Date</label>
        <input type="date" name="" id="" placeholder="Date" class="form-control">
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Maxallowed </label>
        <input type="text" name="" id="" placeholder="maxallowed " class="form-control">
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">Booked Waiting</label>
        <input type="text" name="" id="" placeholder="Booked Waiting" class="form-control">
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label for="">valid Registration</label>
        <input type="text" name="" id="" placeholder="valid Registration" class="form-control">
      </div>
    </div>
  </div>
</div>
<div class="d-flex justify-content-center mt-2 pt-2 mb-4">
  <button class="btn btn-primary">Save</button>&nbsp;
  <button class="btn btn-primary">update</button>
</div>
</div>
</div>
</div>
</div>
<script>
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("hover");
      var panel = this.nextElementSibling;
      if (panel.style.display === "block") {
        panel.style.display = "none";
      } else {
        panel.style.display = "block";
      }
    });
  }

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#profile')
        .attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection
