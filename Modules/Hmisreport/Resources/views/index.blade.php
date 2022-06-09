@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <br>
                <h4 class="col-md-6 col-md-offset-1 text-right">अगाडि बढ्नको लागि मिति छनौट गर्नुहोस् !</h4>
                <section class="form-wrapper">
                    <div class="container">
                        <div class="col-md-6  col-md-offset-2">
                            <br>
                            @if(Session::has('error_message'))
                                <div class="alert alert-danger col">
                                    <strong> {{ Session::get('error_message') }}</strong>
                                </div>
                            @endif
                            @if(Session::has('success_message'))
                                <div class="alert alert-success">
                                    <strong>{{ Session::get('success_message') }} </strong>
                                </div>
                                <br>
                            @endif
                        </div>
                    </div>
                </section>
                <section class="form-wrapper">
                    <div class="container">
                        <div class="panel-body">
                            <!--This is for searching -->

                            <form name="search_form" id="search" method="get" action="{{ route('generate.report') }}">
                                <div class="row col-md-offset-2">

                                    <div class="form-group col-md-3">
                                        <label>Fiscal Year</label>
                                        <div class="input-group">
                                            <select name="fiscal_year" id="fiscal_year" class="form-control" required>
{{--                                                @php--}}
{{--                                                    $fiscals = explode('-',$fiscals);--}}
{{--                                                @endphp--}}
                                                @forelse($fiscals as $fiscal)
                                                    <option value="{{$fiscal->fldname ? $fiscal->fldname : null}}" {{ \App\Utils\Helpers::getNepaliFiscalYearHMIS() ==  $fiscal->fldname ? 'selected' : '' }} >{{$fiscal->fldname ? $fiscal->fldname : null}} </option>
{{--                                                    <option>{{ $fiscals[0] ? $fiscals[0].'/' : null }}{{$fiscals[1] ? $fiscals[1] : null}} </option>--}}
                                                @empty
                                                    <option>No data available</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <strong><small class="help-block text-danger">{{$errors->first('report_date')}}</small> </strong>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Choose Month</label>
                                        <div class="input-group">
                                            <select name="month" id="month" class="form-control" required>
                                                <option value="">--Select--</option>
                                                <option value="4" >श्रावण</option>
                                                <option value="5">भदौ</option>
                                                <option value="6">असोज</option>
                                                <option value="7">कार्तिक</option>
                                                <option value="8">मंसिर</option>
                                                <option value="9">पौष</option>
                                                <option value="10">माघ</option>
                                                <option value="11">फागुन</option>
                                                <option value="12">चैत्र</option>
                                                <option value="1">वैशाख</option>
                                                <option value="2">ज्येष्ठ</option>
                                                <option value="3">असार</option>
                                            </select>
                                        </div>
                                        <strong><small
                                                class="help-block text-danger">{{$errors->first('report_date')}}</small>
                                        </strong>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>From Date</label>
                                        <div class="input-group">
                                            <input type="text" name="report_date"  id="report_date" class="form-control datepicker" autocomplete="off"
                                                   value="" required >
                                        </div>
                                        <strong><small class="help-block text-danger">{{$errors->first('report_date')}}</small> </strong>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>To Date</label>
                                        <div class="input-group">
                                            <input type="text" name="to_date"  id="to_date" class="form-control datepicker" autocomplete="off"  required  >
                                        </div>
                                        <strong><small class="help-block text-danger">{{$errors->first('to_date')}}</small> </strong>
                                    </div>

                                </div>

                                <div class="row col-md-offset-3">
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <input type="submit" name="submit"  value="Generate" class="btn btn-primary" id="btnGenerateReport">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <a href="{{ route('admin.dashboard') }}" name="Cancel" class=" btn btn-danger">Cancel </a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!--End searching -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div id="hmisProcessingModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="padding: 13px 30px;">                
                    <h4 class="modal-title">
                        <!-- <img src="{{ asset('img/dashboard_icons/cog-logo.png') }}" class="logo" alt="logo" style="max-height: 45px;"> -->
                        HMIS 9.4 Reporting
                    </h4>
                    <a class="close" href="">&times;</a>
                </div>
                <div class="modal-body">
                    <h3 style="text-align: center;color: #000000;font-weight: 500;">
                        <i class="fa fa-spinner fa-spin"></i> Processing Report ....
                    </h3>
                    <p style="color: #000000;font-size: 14px;text-align: center;margin-top: 25px;">Please wait as this may take a few minutes.</p>
                </div>
                <div class="modal-footer">
                    <a href="" class="btn btn-default">Close</a>
                </div>
            </div>

        </div>
    </div>


    <script>
        var data =$('#fiscal_year').val().toString();
        if( data.includes("-")){
            data = data.replace("-", "/");
        }
        // data = data.replace("-", "/");
        var arr = data.split('/');
        var from_date = arr[0] ? '20'+arr[0]+'-04-01' : '';
        var to_date =   arr[1] ? '20'+arr[1]+'-03-31' : '';

        $(document).ready(function () {

            from_date ? $('#report_date').val(from_date) : '';
            to_date ? $('#to_date').val(to_date) : '';

            // $("#report_date").prop("readonly", true);
            // $("#to_date").prop("readonly", true);
            // var date = new Date();
            // var date_string = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
            // // var date_string = date.getFullYear() + '-' + ( '0' + date.getMonth() + 1).slice(-2) + '-' + ( '0' + date.getDate()).slice(-2);
            // var nepaliDate = AD2BS(date_string);
            // (nepaliDate) ? $('#report_date').val(nepaliDate) : null;
            // (nepaliDate) ? $('#to_date').val(nepaliDate) : null;

            $('#btnGenerateReport').click(function (event) {
                $('#hmisProcessingModal').modal({backdrop: 'static', keyboard: false, show: true});
            })

        });

        $('.datepicker').nepaliDatePicker({
                //
                // disableBefore: "2076-04-01",
                // disableAfter: "2076-0-01"
        });

        $(document).on('change', '#fiscal_year', function () {
            var data = $(this).val();
            if( data.includes("-")){
                data = data.replace("-", "/");
            }
            // data = data.replace("-", "/");
            var arr = data.split('/');
            var month = $('#month').val();
            if(month == ''){
                showAlert('Month cannot be empty','error');
                return false;
            }
            var from_date = arr[0] ? '20' + arr[0] + '-04-01' : '';
            var to_date = '';
            var url = "{{ route('get.last.date') }}"
            if (arr[0] != '' || arr[1] != '') {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        year: arr[1],
                        month: month,
                    },
                    dataType: "json",
                    success: function (response) {

                        if (response) {
                            to_date = arr[1] ? '20' + arr[0]  +((month.length <= 1) ? ('-0'+month) : ('-'+ month)) + '-' + response + '' : '';
                            from_date ? $('#report_date').val(from_date) : '';
                            to_date ? $('#to_date').val(to_date) : '';
                        }

                        if(month=='' && response.without_month){
                            $('#to_date').val(response.without_month)
                        }

                        if (response.error) {
                            alert(response.error, 'error');

                        }
                    }
                });
            }else {
                showAlert('Something went wrong','error');
            }
            // from_date ? $('#report_date').val(from_date) : '';
            // to_date ? $('#to_date').val(to_date) : '';
        })

        $(document).on('change', '#month', function () {
            var month = $('#month').val();
            var data = $('#fiscal_year').val();
            if( data.includes("-")){
                data = data.replace("-", "/");
            }
            if(month == ''){
                showAlert('Month cannot be empty','error');
                return false;
            }
            // data = data.replace("-", "/");
            var arr = data.split('/');
            var from_date = arr[0] ? '20' + ((month == 1 || month == 2 || month == 3) ? arr[1] : arr[0] ) + ((month.length <= 1) ? ('-0'+month) : ('-'+ month)) + '-01' : '';
            var to_date = ''

            var url = "{{ route('get.last.date') }}"
            if (arr[0] != '' || arr[1] != '') {
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        year: arr[1],
                        month: month,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response) {
                            to_date = arr[1] ? '20' + ((month == 1 || month == 2 || month == 3) ? arr[1] : arr[0] )  +((month.length <= 1) ? ('-0'+month) : ('-'+ month)) + '-' + response + '' : '';
                            from_date ? $('#report_date').val(from_date) : '';
                            to_date ? $('#to_date').val(to_date) : '';
                        }
                        if (response.error) {
                            alert(response.error, 'error');

                        }
                    }
                });
            }else {
                showAlert('Something went wrong','error');
            }

        })


        // $(formWrapper_anc).on('click', '.remove-button', function (e) {
        //     $(this).closest('.remove-form-block-anc').remove();
        // });
    </script>
@endsection()
