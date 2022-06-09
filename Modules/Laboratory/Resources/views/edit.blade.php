@extends('backend.layouts.master')
@section('content')

<div class="content">

    <style>
        #myInput {
            background-image: url('/css/searchicon.png');
            /* Add a search icon to input */
            background-position: 10px 12px;
            /* Position the search icon */
            background-repeat: no-repeat;
            /* Do not repeat the icon image */
            width: 100%;
            /* Full-width */
            font-size: 16px;
            /* Increase font-size */
            padding: 12px 20px 12px 40px;
            /* Add some padding */
            border: 1px solid #ddd;
            /* Add a grey border */
            margin-bottom: 12px;
            /* Add some space below the input */
        }

        #myUL {
            /* Remove default list styling */
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #myUL li a {
            border: 1px solid #ddd;
            /* Add a border to all links */
            margin-top: -1px;
            /* Prevent double borders */
            background-color: #f6f6f6;
            /* Grey background color */
            padding: 12px;
            /* Add some padding */
            text-decoration: none;
            /* Remove default text underline */
            font-size: 18px;
            /* Increase the font-size */
            color: black;
            /* Add a black text color */
            display: block;
            /* Make it into a block element to fill the whole list */
        }

        #myUL li a:hover:not(.header) {
            background-color: #eee;
            /* Add a hover effect to all links, except for headers */
        }
    </style>
    <div class="row">
        <div class="col-lg-8">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                    Edit Lab Test
                </div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal" action="{{ route('admin.laboratory.update',$test->fldtestid) }}">
                        {{ csrf_field() }}
                        <div class="form-group"><label class="col-sm-2 control-label">Test Name</label>

                            <div class="col-sm-10"><input type="text" name="fldtestid" class="form-control" value="{{ $test->fldtestid }}"></div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label">Category</label>


                            <div class="col-sm-6">
                                <select name="fldcategory" class="form-control m-b">
                                    <option value=''></option>
                                    @if($pathocategory)
                                    @foreach($pathocategory as $cat)

                                    <option value="{{ $cat->flclass }}" @if($cat->flclass == $test->fldcategory) selected @endif >{{ $cat->flclass }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#pathocategory">
                                    +
                                </button>
                            </div>




                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Sys Constant</label>


                            <div class="col-sm-10"><select name="fldsysconst" class="form-control m-b">
                                    <option value=''></option>
                                    @if($sysconst)
                                    @foreach($sysconst as $sample)
                                    <option value="{{ $sample->fldsysconst }}" @if($sample->fldsysconst == $test->fldsysconst) selected @endif>{{ $sample->fldsysconst }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- Trigger the modal with a button -->
                                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#sysconst">
                                    +
                                </button>
                            </div>


                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Specimen</label>


                            <div class="col-sm-10"><select name="fldspecimen" class="form-control m-b">
                                    <option value=''></option>
                                    @if($sampletype)
                                    @foreach($sampletype as $sample)
                                    <option value="{{ $sample->fldsampletype }}" @if($sample->fldsampletype == $test->fldspecimen) selected @endif>{{ $sample->fldsampletype }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- Trigger the modal with a button -->
                                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#sampletype">
                                    +
                                </button>
                            </div>


                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Collection</label>

                            <div class="col-sm-10"><input type="text" name="fldcollection" class="form-control" value="{{ $test->fldcollection }}"></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Sensitivity</label>

                            <div class="col-sm-10"><input type="number" name="fldsensitivity" class="form-control" value="{{ $test->fldsensitivity }}"></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Specificity</label>

                            <div class="col-sm-10"><input type="number" name="fldspecificity" class="form-control" value="{{ $test->fldspecificity }}"></div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label">Data Type</label>

                            <div class="col-sm-10"><select name="fldtype" class="form-control m-b" name="account">
                                    <option value="0"></option>
                                    <option @if($test->fldtype == 'Qualitative') selected @endif>Qualitative</option>
                                    <option @if($test->fldtype == 'Quantitative') selected @endif>Quantitative</option>

                                </select>
                            </div>
                        </div>
                        <!-- data type one change  value appears in this -->
                        <div class="form-group"><label class="col-sm-2 control-label">Input Mode</label>

                            <div class="col-sm-10"><select name="fldoption" class="form-control m-b" name="account">
                                    <option value='0'></option>
                                    <option value="No Selection">No Selection</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <button class="btn btn-default">Options</button>
                                <button class="btn  btn-default">Comment</button>
                            </div>
                        </div>


                        <div class="form-group"><label class="col-sm-2 control-label">Outliers</label>


                            <div class="col-sm-4"> <span>Ref Range +-</span><input type="text" name="fldcritical" class="form-control"><span>X Ref Range</span></div>

                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">

                                <button class="btn  btn-default">Chemical</button>
                            </div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label">Description</label>

                            <div class="col-sm-10"><textarea class="form-control" cols=10 rows=8 name="flddetail">{{ $test->flddetail }} </textarea></div>
                        </div>

                        <div class="form-group"><label class="col-sm-2 control-label">FootNote</label>

                            <div class="col-sm-10"><textarea class="form-control" cols=10 rows=8 name="fldcomment">{{ $test->fldcomment }} </textarea></div>
                        </div>

                        <div class="col-sm-8 col-sm-offset-2">
                            <a class="btn btn-default" href="{{ route('admin.laboratory') }}">Cancel</a>
                            <button class="btn btn-primary" type="submit">Edit changes</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                    Lab Test
                </div>

                <div class="panel-body">
                    <div class="input-group">
                        <input class="form-control" type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Tests..">
                        <!-- <div class="input-group-btn">
                            <button class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div> -->
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-lg-12">
                            <ul id="myUL">
                                @if($tests)
                                @foreach($tests as $t)
                                <li> <a href="javascript:;" class="itemlab" redirectlocation="{{ route('admin.laboratory.edit',$t->fldtestid) }}">{{ $t->fldtestid }}</a></li>
                                @endforeach
                                @endif
                            </ul>








                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="pathocategory" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Patho Category</h4>
                </div>
                <div class="modal-body">
                    <form>
                        {{ csrf_field() }}
                        <input type="hidden" name="workin" class="workin" value="{{ route('admin.laboratory.addpathocategory') }}" />
                        <div class="form-group"><label class="col-sm-2 control-label">Category</label>

                            <div class="col-sm-10"><input type="text" name="fldcategory" class="form-control" value="Test" readonly></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10"><input type="text" name="fldsysconst" class="form-control"></div>
                        </div>
                        <div>
                            <ul id="addnewpathocategory">

                                @if($pathocategory)
                                @foreach($pathocategory as $cat)

                                <li>{{ $cat->flclass }}<a href="{{ route('admin.laboratory.deletepathocat',$cat->fldid) }}">X</a></li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="addpathocategory">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="sysconst" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Sys Const</h4>
                </div>
                <div class="modal-body">
                    <form>
                        {{ csrf_field() }}
                        <input type="hidden" name="workin" class="workin" value="{{ route('admin.laboratory.addsysconst') }}" />
                        <div class="form-group"><label class="col-sm-2 control-label">Category</label>

                            <div class="col-sm-10"><input type="text" name="fldcategory" class="form-control" readonly></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10"><input type="text" name="fldsysconst" class="form-control"></div>
                        </div>
                        <div>
                            <ul id="addnewsysconst">

                                @if($sysconst)
                                @foreach($sysconst as $cat)

                                <li>{{ $cat->fldsysconst }}<a href="">X</a></li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="addsysconst">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="sampletype" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Sample Type</h4>
                </div>
                <div class="modal-body">
                    <form>
                        {{ csrf_field() }}
                        <input type="hidden" name="workin" class="workin" value="{{ route('admin.laboratory.addsampletype') }}" />
                        <div class="form-group"><label class="col-sm-2 control-label">Category</label>

                            <div class="col-sm-10"><input type="text" name="fldcategory" class="form-control" readonly></div>
                        </div>
                        <div class="form-group"><label class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10"><input type="text" name="fldsysconst" class="form-control"></div>
                        </div>
                        <div>
                            <ul id="addnewsampletype">

                                @if($sampletype)
                                @foreach($sampletype as $cat)

                                <li>{{ $cat->fldsampletype }}<a href="">X</a></li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="addsampletype">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#addpathocategory").click(function(e) {
            e.preventDefault();
            var formData = $(this).closest('form').serialize();
            urlaction = $(this).closest('form').find('.workin').val();
            var fldsysconst = $('#fldsysconst').val();
           // alert(urlaction);
            $.ajax({
                url: urlaction,
                type: 'POST',
                dataType: "json",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        $('#addnewpathocategory li:last-child').after('<li>' + data.success.name + '<a href="">X</a></li>');
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });



        });

        $("#addsysconst").click(function() {
            var formData = $(this).closest('form').serialize();
            urlaction = $(this).closest('form').find('.workin').val();
            var fldsysconst = $('#fldsysconst').val();
            $.ajax({
                url: urlaction,
                type: 'POST',
                dataType: "json",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        $('#addnewsysconst li:last-child').after('<li>' + data.success.name + '<a href="">X</a></li>');
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });



        });

        $("#addsampletype").click(function() {
            var formData = $(this).closest('form').serialize();
            urlaction = $(this).closest('form').find('.workin').val();
            var fldsysconst = $('#fldsysconst').val();
            $.ajax({
                url: urlaction,
                type: 'POST',
                dataType: "json",
                data: formData,
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        //location.reload();
                        $('#addnewsampletype li:last-child').after('<li>' + data.success.name + '<a href="">X</a></li>');
                    } else {
                        printErrorMsg(data.error);
                    }
                }
            });



        });

        function printErrorMsg(msg) {
            $("#error").html('');
            $("#error").css('display', 'block');
            $.each(msg, function(key, value) {
                $("#error").html(value);
            });
        }

        $(".itemlab").click(function() {
            var redirectlocation = $(this).attr('redirectlocation');
            //alert($(this).attr('itemlab'));
            window.location.href = redirectlocation;

        });
    </script>
    <script>
        function myFunction() {
            // Declare variables
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('myInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName('li');

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
</div>
@endsection