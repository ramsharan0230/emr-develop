@extends('frontend.layouts.master')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Edit Dynamic Report</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <form id="dynamicform" action="{{route('update.dynamic.report')}}" method="POST" class="form-horizontal">
                                    @csrf
                                    <input type="hidden" name="dynamic_report_id" id="dynamic_report_id" value="{{$reportData->id}}">
                                    <input type="hidden" name="sidebar_menu_id" id="sidebar_menu_id" value="{{$reportData->fldsidebarmenuid}}">
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Report Name*</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="reportname" id="reportname" class="form-control" placeholder="Enter Report Name" value="{{$reportData->fldreportname}}" required>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Slug*</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="reportslug" id="reportslug" class="form-control" value="{{$reportData->fldreportslug}}" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Sidebar Menu*</label>
                                        <div class="col-sm-9">
                                            <select name="sidebarmenu" id="sidebarmenu" class="form-control" required>
                                                @foreach($mainmenus as $mainmenu)
                                                    <option value="{{ $mainmenu->mainmenu }}" @if($reportData->fldsidebarmodule == $mainmenu->mainmenu) selected @endif>{{ $mainmenu->mainmenu }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3">Query*</label>
                                        <div class="col-sm-9">
                                            <textarea name="fldquery" id="query" class="form-control" rows="8" required>{{str_replace("'?'","?",$reportData->fldquery)}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Labels</label>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="table-responsive" style="max-height: none;">
                                                <table class="table table-striped table-hover table-bordered ">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th><input type="checkbox" id="js-label-showall-checkbox"></th>
                                                        <th>Field Name</th>
                                                        <th>Assign Report Name</th>
                                                        <th>Align Type</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="label_result">
                                                        @foreach ($labels as $key=>$label)
                                                            <tr>
                                                                <td><input type="checkbox" class="js-label-checkbox fieldSelected" name="labels[{{$key}}][fieldSelected]" value="1" @if(array_key_exists("fieldSelected",$label)) @if($label['fieldSelected'] == 1) checked @endif @endif></td>
                                                                <td><input type="text" name="labels[{{$key}}][colname]" class="form-control colname" value="{{($label['colname']) ? $label['colname'] : ''}}" readonly required></td>
                                                                <td><input type="text" name="labels[{{$key}}][assignedName]" class="form-control assignedName" value="{{(array_key_exists("assignedName",$label)) ? $label['assignedName'] : ''}}"></td>
                                                                <td><select name="labels[{{$key}}][alignType]" class="form-control alignType"><option value="left" @if($label['alignType']) @if($label['alignType'] == "left") selected @endif @endif>Left</option><option value="right" @if($label['alignType']) @if($label['alignType'] == "right") selected @endif @endif>Right</option></select></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Conditions</label>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="table-responsive" style="max-height: none;">
                                                <table class="table table-striped table-hover table-bordered ">
                                                    <thead class="thead-light">
                                                    <tr>
                                                        <th>Field Name</th>
                                                        <th>Operator</th>
                                                        <th>Variables / Values</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="condition_result">
                                                        @foreach ($conditions as $key=>$condition)
                                                            <tr>
                                                                <td><input type="text" name="conditions[{{$key}}][whereFields]" value="{{($condition['whereFields']) ? $condition['whereFields'] : ''}}" class="form-control" readonly required></td>
                                                                <td><input type="text" name="conditions[{{$key}}][operators]" value="{{($condition['operators']) ? $condition['operators'] : ''}}" class="form-control" readonly required></td>
                                                                <td><input type="text" name="conditions[{{$key}}][values]" value="{{($condition['values']) ? $condition['values'] : ''}}" class="form-control" readonly required></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-row align-items-center">
                                        <label for="" class="col-sm-3"></label>
                                        <div class="col-sm-9">
                                            <button type="submit" id="saveForm" class="btn btn-primary btn-action">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h4 class="card-title">Dynamic Report Lists</h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="table-responsive" style="max-height: none;">
                                    <table class="table table-striped table-hover table-bordered ">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>SNo.</th>
                                            <th>Report Name</th>
                                            <th>Slug</th>
                                            <th>Sidebar Menu</th>
                                            <th>Query</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="table_result">
                                            {!!$html!!}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('after-script')
    <script src="{{ asset('assets/js/mysql.umd.js') }}"></script>
    <script>
        var error = false;
        $(document).on('keyup','#reportname',function(){
            var reportname = $(this).val();
            var slug = slugify(reportname);
            $('#reportslug').val(slug);
        });

        function slugify(content) {
            return content.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
        }

        $(document).on('click','#saveForm',function(e){
            e.preventDefault();

            if(checkIdValidation("reportname") == true){
                error = true;
            }
            if(checkIdValidation("reportslug") == true){
                error = true;
            }
            if(checkIdValidation("query") == true){
                error = true;
            }
            $('.fieldSelected').each(function (i, option) {
                if($(option).is(":checked")){
                    customRequiredValidation($(this),"td");
                }else{
                    $(this).closest("td").find('.error').html("");
                }
            });
            $('.colname').each(function (i, option) {
                if($(option).closest('tr').find('.fieldSelected').is(":checked")){
                    customRequiredValidation($(this),"td");
                }else{
                    $(this).closest("td").find('.error').html("");
                }
            });
            $('.assignedName').each(function (i, option) {
                if($(option).closest('tr').find('.fieldSelected').is(":checked")){
                    customRequiredValidation($(this),"td");
                }else{
                    $(this).closest("td").find('.error').html("");
                }
            });
            $('.alignType').each(function (i, option) {
                if($(option).closest('tr').find('.fieldSelected').is(":checked")){
                    customRequiredValidation($(this),"td");
                }else{
                    $(this).closest("td").find('.error').html("");
                }
            });
            var chkSelected = [];
            var selectedData = $.map($('#label_result tr'), function(trElem, i) {
                if($(trElem).find('.js-label-checkbox').is(":checked")){
                    chkSelected.push($(trElem).val());
                }
            });
            if(!(chkSelected.length > 0)){
                showAlert("Please select label!","error");
                return false;
            }
            console.log(error);
            if(error == false){
                $("#dynamicform").submit();
            }
        });

        $(document).on('blur','#query',function(){
            var query = $('#query').val();
            query.replace("?", "'?'");
            const parser = new NodeSQLParser.Parser();
            const ast = parser.astify(query);
            getSqlColumns(ast.columns);
            var whereFields = (ast.where != null) ? getWhereFields(ast.where) : null;
            var whereOperators = (ast.where != null) ? getOperator(ast.where) : null;
            var whereValues = (ast.where != null) ? getWhereValues(ast.where) : null;
            if((whereFields != null) && (whereOperators != null) && (whereValues != null)){
                setConditions(whereFields,whereOperators,whereValues);
            }else{
                $('#condition_result').html("");
            }
        });

        function setConditions(whereFields,whereOperators,whereValues){
            var html = "";
            $.each(whereFields, function(i, data) {
                html += '<tr>'+
                        '<td><input type="text" name="conditions['+i+'][whereFields]" value="'+data+'" class="form-control" readonly required></td>'+
                        '<td><input type="text" name="conditions['+i+'][operators]" value="'+whereOperators[i]+'" class="form-control" readonly required></td>'+
                        '<td><input type="text" name="conditions['+i+'][values]" value="'+whereValues[i]+'" class="form-control" readonly required></td>'+
                        '</tr>';
            });
            $('#condition_result').html(html);
        }

        function getSqlColumns(columnArray){
            var html = "";
            $.each(columnArray, function(i, data) {
                var colname = "";
                if(data.as != null){
                    if(data.expr.table != null){
                        colname = ""+data.expr.table+"."+data.expr.column+" as "+data.as;
                    }else{
                        colname = ""+data.expr.column+" as "+data.as;
                    }
                }else{
                    if(data.expr.table != null){
                        colname = ""+data.expr.table+"."+data.expr.column;
                    }else{
                        colname = data.expr.column;
                    }
                }
                html += '<tr>'+
                        '<td><input type="checkbox" class="js-label-checkbox fieldSelected" name="labels['+i+'][fieldSelected]" value="1"></td>'+
                        '<td><input type="text" name="labels['+i+'][colname]" value="'+colname+'" class="form-control colname" readonly required></td>'+
                        '<td><input type="text" name="labels['+i+'][assignedName]" class="form-control assignedName"></td>'+
                        '<td><select name="labels['+i+'][alignType]" class="form-control alignType"><option value="left">Left</option><option value="right">Right</option></select></td>'+
                        '</tr>';
            });
            $('#label_result').html(html);
        }


        function getWhereFields(obj, result = []) {
            let value;
            Object.keys(obj).forEach(key => {
                value = obj[key];
                if (value instanceof Object) {
                    getWhereFields(value, result);
                } else if (key === "column") {
                    result.push(value);
                }
            });
            return result;
        }

        function getWhereValues(obj, result = []) {
            let value;
            Object.keys(obj).forEach(key => {
                value = obj[key];
                if (value instanceof Object) {
                    getWhereValues(value, result);
                } else if (key === "value") {
                    result.push(value);
                }
            });
            return result;
        }

        function getOperator(obj, result = []) {
            let value;
            Object.keys(obj).forEach(key => {
                value = obj[key];
                if (value instanceof Object) {
                    getOperator(value, result);
                } else if (key === "operator") {
                    if((value != "AND") && (value != "and")){
                        result.push(value);
                    }
                }
            });
            return result;
        }

        $(document).on('change','#js-label-showall-checkbox',function(){
            if($(this).is(":checked")){
                $.each($('.js-label-checkbox'), function(i, option) {
                    $(option).prop('checked','checked');
                });
            }else{
                $.each($('.js-label-checkbox'), function(i, option) {
                    $(option).prop('checked',false);
                });
            }
        });

        function customRequiredValidation(currentElement,elementName){
            if(currentElement.val() == "" || currentElement.val().length < 1) {
                currentElement.closest(elementName).find('.error').html("");
                currentElement.closest(elementName).append("<label class='error'>This field is required.</label>");
                error = true;
            } else {
                currentElement.closest(elementName).find('.error').html("");
            }
        }

        function checkIdValidation(idName){
            var hasError = false;
            if($('#'+idName).val() == ""){
                hasError = true;
                if($('#'+idName).closest('div').find('.error').length == 0){
                    $('#'+idName).closest('div').append('<span class="error text-danger">This field is required</span>');
                }
            }else{
                if($('#'+idName).closest('div').find('.error').length != 0){
                    $('#'+idName).closest('div').find('.error').remove();
                }
            }
            return hasError;
        }

    </script>
@endpush
