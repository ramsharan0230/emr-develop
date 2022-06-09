@extends('frontend.layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">
                               Prefix Setting
                            </h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                          <table class="table no-border">
                            <tbody>
                                <tr>
                                    <td class="border-none bt-none"></td>
                                    <td class="border-none bt-none">Prefix Text</td>
                                    <td class="border-none bt-none">Integer Length</td>
                                </tr>
                                <!-- encounter -->
                                <tr>
                                  <td rowspan="2" class="text-center border-none">Encounter Id</td>
                                </tr>
                                <tr>
                                  <td class="border-none"> <input type="text" class="form-control" id="fldencid" value="{{$prefix->fldencid??''}}"></td>
                                  <td class="border-none"> <input type="text" class="form-control" id="fldpatlen" value="{{$prefix->fldpatlen??''}}"></td>
                                </tr>
                                <!-- Patient no -->
                                <tr>
                                    <td rowspan="2" class="text-center border-none">Patient Number</td>
                                </tr>
                                <tr>
                                  <td class="border-none"><input type="text" class="form-control" id="fldencid" value="{{$prefix->fldencid??''}}"></td>
                                  <td class="border-none"><input type="text" class="form-control" id="fldpatlen" value="{{$prefix->fldpatlen??''}}"></td>
                                </tr>
                                <!-- booking -->
                                <tr>
                                    <td rowspan="2" class="text-center border-none">Booking Number</td>
                                </tr>
                                <tr>
                                  <td class="border-none">  <input type="text" class="form-control" id="fldbooking" value="{{$prefix->fldbooking??''}}"></td>
                                  <td class="border-none">  <input type="text" class="form-control" id="fldbooklen" value="{{$prefix->fldbooklen??''}}"></td>
                                </tr>
                                 <!-- Regular, Family and other added by anish-->

                                 <!-- Regular -->
                                 <tr>
                                    <td rowspan="2" class="text-center border-none">Regular</td>
                                </tr>
                                <tr>
                                  <td class="border-none"><input type="text" class="form-control" id="fldregular" value="{{$prefix->fldregular??''}}"></td>
                                  <td class="border-none"><input type="text" class="form-control" id="fldregularlen" value="{{$prefix->fldregularlen??''}}"></td>
                                </tr>
                                 <!-- Family -->
                                 <tr>
                                    <td rowspan="2" class="text-center border-none"> Family</td>
                                </tr>
                                <tr>
                                  <td class="border-none"><input type="text" class="form-control" id="fldfamily" value="{{$prefix->fldfamily??''}}"></td>
                                  <td class="border-none"><input type="text" class="form-control" id="fldfamilylen"value="{{$prefix->fldfamilylen??''}}"></td>
                                </tr>
                                 <!-- Family -->
                                 <tr>
                                    <td rowspan="2" class="text-center border-none"> Other</td>
                                </tr>
                                <tr>
                                  <td class="border-none"><input type="text" class="form-control" id="fldother" value="{{$prefix->fldother??''}}"></td>
                                  <td class="border-none"><input type="text" class="form-control" id="fldotherlen" value="{{$prefix->fldotherlen??''}}"></td>
                                </tr>
                                <tr>
                                  <td class="border-none"></td>
                                  <td class="border-none">
                                   <a href="javascript:;" id="update-prefix" url="{{route('updateprefix')}}" type="button" class="btn btn-primary btn-action">
                                    <i class="fa fa-edit"></i> Update</a></td>
                                </tr>
                              </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-script')

    <script>
        $("#update-prefix").click(function () {

            var url = $(this).attr('url');
            var fldpatno = $("#fldpatno").val();
            var fldpatlen = $("#fldpatlen").val();
            var fldencid = $("#fldencid").val();
            var fldenclen = $("#fldenclen").val();
            var fldbooking = $("#fldbooking").val();
            var fldbooklen = $("#fldbooklen").val();
            var fldhospcode = $("#fldhospcode").val();

            //Extra 3 Added by anish
            var fldregular = $("#fldregular").val();
            var fldregularlen = $("#fldregularlen").val();
            var fldfamily = $("#fldfamily").val();
            var fldfamilylen = $("#fldfamilylen").val();
            var fldother = $("#fldother").val();
            var fldotherlen = $("#fldotherlen").val();


            var formData = {
                fldpatno: fldpatno,
                fldpatlen: fldpatlen,
                fldencid: fldencid,
                fldenclen: fldenclen,
                fldbooking: fldbooking,
                fldbooklen: fldbooklen,
                fldhospcode: fldhospcode,
                //Added by Anish
                fldregular: fldregular,
                fldregularlen: fldregularlen,
                fldfamily: fldfamily,
                fldfamilylen: fldfamilylen,
                fldother: fldother,
                fldotherlen: fldotherlen,

            };


            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: formData,
                success: function (data) {
                    if ($.isEmptyObject(data.error)) {

                        showAlert("Information saved!!");
                        //location.reload();
                    } else {
                        alert("Something went wrong!!");
                    }
                }
            });
        });


    </script>
@endpush
