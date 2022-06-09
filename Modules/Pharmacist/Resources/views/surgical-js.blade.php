<script>

    CKEDITOR.replace('flddetail',
                {
                    height: '200px',
                });

    $(document).on('change','#surgicalNames',function(){
        var surgId = $(this).val();
        var surgName = $( "#surgicalNames option:selected" ).text();
        if(surgId != ""){
            $.ajax({
                url: '{{ route("surgicalNameInfo") }}',
                type: 'get',
                dataType: 'json',
                data: {
                    'surgId' : surgId,
                },
                success: function(res) {
                    if(!res.status){
                        showAlert(res.message,"error");
                    } else if(res.status == true) {
                        clearSurgOrthoFields();
                        clearSutureFields();
                        clearBrandFields();
                        $('#main-tab').css('display','flex');
                        $('.tab-content').css('display','block');
                        $('#msurg-ortho_tab').removeAttr("data-toggle");
                        $('#suture_tab').removeAttr("data-toggle");
                        $('#brand_tab').removeAttr("data-toggle");
                        $('#surgicallistingtable').html(res.html);
                        $('#surgFldId').val(res.surgicalNameData.fldid);
                        $('#fldsurgcateg').val(res.surgicalNameData.fldsurgcateg).trigger('change');
                        $('#fldsurgname').val(res.surgicalNameData.fldsurgname);
                        if(res.surgicalCategory == "suture"){
                            $('#fldsuturename').val(surgName)
                            $('#sutureSurgCateg').val(res.surgicalCategory);
                            toggleActiveTabs("suture_tab");
                        }else{
                            $('#flditemname').val(surgName)
                            $('#surgCateg').val(res.surgicalCategory);
                            toggleActiveTabs("msurg-ortho_tab");
                        }
                    }
                }
            });
        }else{
            $('#main-tab').css('display','none');
            $('.tab-content').css('display','none');
            $('#surgicallistingtable').html("");
        }
    });

    $(document).on('click','.selectsurgical',function(){
        var surgId = $(this).attr('data-surgid');
        jQuery('.surg-brand-ul').each(function() {
            var curr = $(this).find('.surg-brand');
            if(surgId != curr.attr('data-surgid')){
                if(curr.hasClass('show')){
                    $(this).css('display','none');
                    curr.removeClass('show');
                }
            }
        });
        var surgBrand = $(this).closest('.surgical').find('.surg-brand-ul');
        jQuery(surgBrand).each(function() {
            var current = $(this).find('.surg-brand');
            if(current.hasClass('show')){
                $(this).css('display','none');
                current.removeClass('show');
            }else{
                $(this).css('display','inline-block');
                current.addClass('show');
            }
        });
    });

    $(document).on('click','.editsurgical',function(){
        var fldsurgid = $(this).attr('data-surgid');
        $('#brand-fldsurgid').val(fldsurgid);
        $('#brand-fldid').val($("#surgicalNames").val());
        $.ajax({
            url: '{{ route("get.surgical.data") }}',
            type: 'get',
            dataType: 'json',
            data: {
                'fldsurgid' : fldsurgid,
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,"error");
                } else if(res.status == true) {
                    clearSurgOrthoFields();
                    clearSutureFields();
                    clearBrandFields();
                    $('.brand-table-list').html('');
                    if(res.surgicalData.fldsurgcateg == "ortho" || res.surgicalData.fldsurgcateg == "msurg"){
                        $('#flditemname').val(res.surgicalData.fldsurgname);
                        $('#flditemsize').val(res.surgicalData.fldsurgsize);
                        $('#flditemtype').val(res.surgicalData.fldsurgtype);
                        $('#surgCateg').val(res.surgicalData.fldsurgcateg);
                        toggleActiveTabs("msurg-ortho_tab");
                    }else{
                        $('#fldsuturename').val(res.surgicalData.fldsurgname);
                        $('#fldsurgsize').val(res.surgicalData.fldsurgsize);
                        $('#fldsurgtype').val(res.surgicalData.fldsurgtype).trigger('change');
                        $('#fldsurgcode').val(res.surgicalData.fldsurgcode);
                        $('#sutureSurgCateg').val(res.surgicalData.fldsurgcateg);
                        toggleActiveTabs("suture_tab");
                    }
                    $('#brand-fldsurgid').val(fldsurgid);
                    $('#brand-fldid').val($("#surgicalNames").val());
                    $('.brand-table-list').html(res.brandHtml);
                }
            }
        });
    });

    $(document).on('click', '.row-links td:not(.deletebrand)', function(){
        editBrand($(this).closest('.row-links'));
    });

    $(document).on('click','.editbrand',function(){
        editBrand($(this));
    });

    function editBrand(current){
        var fldbrandid = current.attr('data-brandid');
        var fldbrand = current.attr('data-brand');
        var fldsurgid = current.attr('data-surgid');
        $('#brand-fldsurgid').val(fldsurgid);
        $('#brand-fldid').val($("#surgicalNames").val());
        $.ajax({
            url: '{{ route("get.surgical-brand.data") }}',
            type: 'get',
            dataType: 'json',
            data: {
                'fldbrandid' : fldbrandid,
                'fldbrand' : fldbrand,
                'fldsurgid' : fldsurgid
            },
            success: function(res) {
                if(!res.status){
                    alert(res.errormessage);
                } else if(res.status == true) {
                    if(res.surgicalData.fldsurgcateg == "ortho" || res.surgicalData.fldsurgcateg == "msurg"){
                        $('#flditemname').val(res.surgicalData.fldsurgname);
                        $('#flditemsize').val(res.surgicalData.fldsurgsize);
                        $('#flditemtype').val(res.surgicalData.fldsurgtype);
                        $('#surgCateg').val(res.surgicalData.fldsurgcateg);
                    }else{
                        $('#fldsuturename').val(res.surgicalData.fldsurgname);
                        $('#fldsurgsize').val(res.surgicalData.fldsurgsize);
                        $('#fldsurgtype').val(res.surgicalData.fldsurgtype).trigger('change');
                        $('#fldsurgcode').val(res.surgicalData.fldsurgcode);
                        $('#sutureSurgCateg').val(res.surgicalData.fldsurgcateg);
                    }
                    $('.brand-table-list').html(res.brandHtml);

                    $('#brand_fldbrandid').val(res.brandDetails.fldbrandid);
                    $('#fldbrand').val(res.brandDetails.fldbrand);
                    $('#fldstandard').val(res.brandDetails.fldstandard);
                    $('#fldminqty').val(res.brandDetails.fldminqty);
                    $('#fldmaxqty').val(res.brandDetails.fldmaxqty);
                    $('#fldmanufacturer').val(res.brandDetails.fldmanufacturer);
                    $('#fldvolunit').val(res.brandDetails.fldvolunit);
                    $('#fldtaxable').val(res.brandDetails.fldtaxable).trigger('change');
                    $('#fldtaxcode').val(res.brandDetails.fldtaxcode).trigger('change');
                    // newd
                    $("#fldmrp").val(res.brandDetails.fldmrp);
                    
                    if(res.brandDetails.fldcccharge=="fldcccharge_amt")
                        $('input:radio[name="fldcccharge"]').filter('[value="fldcccharge_amt"]').attr('checked', true);
                    else
                        $('input:radio[name="fldcccharge"]').filter('[value="fldcccharge_percent"]').attr('checked', true);
                    
                    if(res.brandDetails.fldinsurance ==1)
                        $('input:radio[name="fldinsurance"]').filter('[value=1]').attr('checked', true);
                    else
                        $('input:radio[name="fldinsurance"]').filter('[value=0]').attr('checked', true);

                    if(res.brandDetails.fldrefundable ==1)
                        $('input:radio[name="fldrefundable"]').filter('[value=1]').attr('checked', true);
                    else
                        $('input:radio[name="fldrefundable"]').filter('[value=0]').attr('checked', true);
                      
                    if(res.brandDetails.flddiscountable_item ==1)
                        $('input:radio[name="flddiscountable_item"]').filter('[value=1]').attr('checked', true);
                    else
                        $('input:radio[name="flddiscountable_item"]').filter('[value=0]').attr('checked', true);
                        
                    $("#fldcccharg_val").val(res.brandDetails.fldcccharg_val);
                    // end neww

                    $('#fldleadtime').val(res.brandDetails.fldleadtime);
                    $('#fldactive').val(res.brandDetails.fldactive).trigger('change');
                    CKEDITOR.instances['flddetail'].setData(res.brandDetails.flddetail);

                    toggleActiveTabs("brand_tab");
                }
            }
        });
    }

    $(document).on('click','#surgicalNameClear',function(e){
        clearSurgicalNameFields();
    });

    $(document).on('click','#surgicalNameSave',function(e){
        e.preventDefault();
        var surgicalNameId = $('#surgicalNames').val();
        var fldsurgcateg = $('#fldsurgcateg').val();
        var fldsurgname = $('#fldsurgname').val();
        if(fldsurgcateg != "" && fldsurgname != ""){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url: '{{ route("insert.surgical.name.variable") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    'surgicalNameId' : surgicalNameId,
                    'fldsurgcateg' : fldsurgcateg,
                    'fldsurgname' : fldsurgname
                },
                success: function(res) {
                    if(!res.status){
                        showAlert(res.message,'error');
                    } else if(res.status == true) {
                        var newOption = new Option(fldsurgname, res.insertId, false, false);
                        $('#surgicalNames').append(newOption);
                        $('#surgFldId').val(res.insertId);
                        showAlert(res.message);
                    }
                }
            });
        }else{
            alert("Category and Item name is required.");
        }
    });

    // $(document).on('click','#msurgOrthoSave',function(event){
    $('#surgOrthoForm').on('submit', function(event){
        event.preventDefault();
        var flditemname = $('#flditemname').val();
        var flditemsize = $('#flditemsize').val();
        var flditemtype = $('#flditemtype').val();
        var fldsurgcateg = $('#surgCateg').val();
        var fldid = $('#surgicalNames').val();
        if(flditemname != ""){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url:"{{ route('insert.surgical.data') }}",
                method:"POST",
                data: {
                    'fldsurgname' : flditemname,
                    'fldsurgcateg' : fldsurgcateg,
                    'fldsurgsize' : flditemsize,
                    'fldsurgtype' : flditemtype,
                    'fldid' : fldid
                },
                dataType:"json",
                success:function(data){
                    if(data.status){
                        // toggleActiveTabs("brand_tab");
                        $('#surgicallistingtable').html(data.html);
                        $('#brand-fldid').val($('#surgicalNames').val());
                        $('#brand-fldsurgid').val(data.fldsurgid);
                        clearSurgOrthoFields();
                        toggleActiveTabs("brand_tab");
                        showAlert(data.message);
                    }else{
                        showAlert("Something went wrong!!", 'Error');
                    }
                }
            });
        }else{
            alert("Please select Item first!");
        }
    });

    // $(document).on('click','#sutureSave',function(event){
    $('#sutureForm').on('submit', function(event){
        event.preventDefault();
        var fldsuturename = $('#fldsuturename').val();
        var fldsurgsize = $('#fldsurgsize').val();
        var fldsurgtype = $('#fldsurgtype').val();
        var fldsurgcateg = $('#sutureSurgCateg').val();
        var fldsurgcode = $('#fldsurgcode').val();
        var fldid = $('#surgicalNames').val();
        if(flditemname != ""){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url:"{{ route('insert.surgical.data') }}",
                method:"POST",
                data: {
                    'fldsurgname' : fldsuturename,
                    'fldsurgcateg' : fldsurgcateg,
                    'fldsurgsize' : fldsurgsize,
                    'fldsurgtype' : fldsurgtype,
                    'fldsurgcode' : fldsurgcode,
                    'fldid' : fldid
                },
                dataType:"json",
                success:function(data){
                    if(data.status){
                        // toggleActiveTabs("brand_tab");
                        $('#surgicallistingtable').html(data.html);
                        $('#brand-fldid').val($('#surgicalNames').val());
                        $('#brand-fldsurgid').val(data.fldsurgid);
                        clearSutureFields();
                        toggleActiveTabs("brand_tab");
                        showAlert(data.message);
                    }else{
                        showAlert("Something went wrong!!", 'Error');
                    }
                }
            });
        }else{
            alert("Please select Item first!");
        }
    });

    // $(document).on('click','#brandSave',function(event){
    $( "#fldcccharg_val" ).focusout(function() { 
        var cccharge = $('.fldcccharge:checked').val();
        if((cccharge == "fldcccharge_percent") && ( ($("#fldcccharg_val").val()) > 99.99)){
            alert("CC Charge should not be greater than 99.99 %")
            var valChart = $("#fldcccharg_val").val('');
        }
    })
    $('#brandForm').on('submit', function(event){
        event.preventDefault();
        if(parseInt($('#fldminqty').val()) > parseInt($('#fldmaxqty').val())){
            alert('Maximum stock must be greater than minimum stock');
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url:"{{ route('insert.surg.brand') }}",
                method:"POST",
                data: new FormData($('#brandForm')[0]),
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                success:function(data){
                    if(data.status){
                        $('#surgicallistingtable').html(data.html);
                        $('.brand-table-list').html(data.brandHtml);
                        clearBrandFields();
                        showAlert(data.message);
                    }else{
                        showAlert(data.message, 'Error');
                    }
                }
            });
        }
    });

    $(document).on('click','#surgicalNameDelete', function(){
        if(!confirm("Delete?")){
            return false;
        }
        var fldid = $('#surgFldId').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $.ajax({
            url: '{{ route("delete.surgical.name.variable") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                'fldid' : fldid
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,"error");
                } else if(res.status == true) {
                    $('#surgicalNames').val('selectedIndex',0);
                    $('#surgicallistingtable').html("");
                    $("#surgicalNames option[value="+fldid+"]").remove();
                    clearSurgicalNameFields();
                    showAlert(res.message);
                }
            }
        });
    });

    $(document).on('click','.deletesurgical', function(){
        if(!confirm("Delete?")){
            return false;
        }
        var fldsurgid = $(this).attr('data-surgid');
        var fldid = $('#surgicalNames').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $.ajax({
            url: '{{ route("delete.surgical.data") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                'fldsurgid' : fldsurgid,
                'fldid' : fldid
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,"error");
                } else if(res.status == true) {
                    $('#surgicallistingtable').html(res.html);
                    showAlert(res.message);
                }
            }
        });
    });

    $(document).on('click','.deletebrand', function(){
        if(!confirm("Delete?")){
            return false;
        }
        var fldbrandid = $(this).attr('data-brandid');
        var fldsurgid = $(this).attr('data-surgid');
        var fldid = $('#surgicalNames').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $.ajax({
            url: '{{ route("delete.surg.brand") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                'fldbrandid' : fldbrandid,
                'fldsurgid' : fldsurgid,
                'fldid' : fldid
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,"error");
                } else if(res.status == true) {
                    $('.brand-table-list').html(res.brandHtml);
                    $('#surgicallistingtable').html(res.html);
                    showAlert(res.message);
                }
            }
        });
    });

    $(document).on('click','#clearMsurgOrtho',function(){
        clearSurgOrthoFields();
    });

    $(document).on('click','#clearSuture',function(){
        clearSutureFields();
    });

    $(document).on('click','#clearBrand',function(){
        clearBrandFields();
    });

    function clearSurgicalNameFields(){
        $('#surgFldId').val("");
        $('#fldsurgcateg').prop('selectedIndex',0).change();
        $('#fldsurgname').val("");
    }

    function clearSurgOrthoFields(){
        $('#surgOrthoForm').find("input[type=text], input[type=number], input[type=hidden]").val("");
    }

    function clearSutureFields(){
        $('#sutureForm').find("input[type=text], input[type=number], input[type=hidden]").val("");
        $('#sutureForm').find("select").prop('selectedIndex',0).change();
    }

    function clearBrandFields(){
        $('#brandForm').find("input[type=text], input[type=number], input[type=hidden]").val("");
        $('#brandForm').find("select").prop('selectedIndex',0).change();
        CKEDITOR.instances['flddetail'].setData("");
    }

    function toggleActiveTabs(tabName){
        var navItems = ["msurg-ortho_tab","suture_tab","brand_tab"];
        var tabPanes = ["msurg-ortho","suture","brand"];
        var navTabs = {"msurg-ortho_tab": "msurg-ortho", "suture_tab": "suture", "brand_tab": "brand"};
        $.each(navItems, function( index, value ) {
            if($("#"+value).hasClass("active")){
                $("#"+value).removeClass("active");
            }
        });
        $.each(tabPanes, function( index, value ) {
            if($("#"+value).hasClass("active")){
                $("#"+value).removeClass("active");
            }
            if($("#"+value).hasClass("show")){
                $("#"+value).removeClass("show");
            }
        });
        $('#'+tabName).addClass("active");
        $('#'+navTabs[tabName]).addClass("show");
        $('#'+navTabs[tabName]).addClass("active");
    }

    $(document).on('change','#showBrandLists',function(){
        if ($(this).prop("checked") == true) {
            $('#brandTableLists').css("display", "block");
        } else {
            $('#brandTableLists').css("display", "none");
        }
    });

    $(document).on('click','#suture_tab',function(){
        if($('#sutureSurgCateg').val() != ""){
            $(this).attr("data-toggle","tab");
            $(this)[0].click();
        }
    });

    $(document).on('click','#msurg-ortho_tab',function(){
        if($('#surgCateg').val() != ""){
            $(this).attr("data-toggle","tab");
            $(this)[0].click();
        }
    });

    $(document).on('click','#brand_tab',function(){
        if($('#brand-fldsurgid').val() != ""){
            $(this).attr("data-toggle","tab");
            $(this)[0].click();
        }
    });

    // Insert Surgical Type Variable
    $(document).on("click", "#insert_surgical_name_type", function(){
        var fldsuturetype = $("#surgical_type_name").val();
        var fldsuturecode = $("#surgical_type_code").val();
        var url = $(this).attr('url');
        formData = {
            fldsuturetype: fldsuturetype,
            fldsuturecode: fldsuturecode
        }
        if(fldsuturetype == '' || fldsuturecode == ''){
            alert('Please Fill All The Fields');
            return false;
        }
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if (data.status) {
                    $("#surgical_type_name").val('');
                    $("#surgical_type_code").val('');
                    showAlert(data.message);
                    getTestName();
                } else {
                    showAlert(data.message,'error');
                }
            }
        });
    });

    // Delete Surgical Type Variable
    $(document).on("click", "#delete_surgical_name_type", function(){
        var fldid = $(this).attr('rel');
        var url = $(this).attr('url');
        formData = {
            fldid: fldid
        }
        if(fldid == ''){
            alert('Please Select Variable To Delete');
            return false;
        }
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: formData,
            success: function (data) {
                if (data.status) {
                    showAlert(data.message);
                    $(".suture_type_remove_id_"+fldid).remove();
                    $('select[name="get_selected_name_type"]').find('option[rel="'+fldid+'"]').remove();
                } else {
                    showAlert(data.message);
                }
            }
        });
    });

    function getTestName(){
        $('.surgical-type-code-list').empty();
        $('.suture_types').empty();
        var num = 1;
        $.get('surgical/get-all-surgical-types', function(data) {
            $('.suture_types').append('<option>---Select Type---</option>');
            $.each(data, function(index, getType) {
                var html = '';
                html += '<tr rel="'+ getType.fldid +'" rel1="'+ getType.type +'" rel2="'+ getType.code +'" class="select_suture_type_row suture_type_remove_id_'+getType.fldid+'">';
                html += '<td>'+ num +'</td>';
                html += '<td>' + getType.type + '</td>';
                html += '<td>' + getType.code + '</td>';
                html += '</tr>';
                $('.suture_types').append('<option value="'+ getType.type +'" rel="'+ getType.fldid +'" rel1="'+ getType.code +'">'+ getType.type +'</option>');
                $('.surgical-type-code-list').append(html);
                num++;
            });
        });
    }

    $(document).on("click", ".select_suture_type_row", function(){
        selected_td('.surgical-type-code-list tr', this);
        $('#delete_surgical_name_type').attr('rel',$(this).attr('rel'));
        $(".select_suture_type_row").removeClass("select_the_element");
        $(this).addClass("select_the_element");
    });

    $(document).on("click", ".get_suture_type_variable", function(){
        getTestName();
    });

    $(document).on('change','#fldtaxable',function(){
        if($('#fldtaxable').val() == "Yes"){
            if($('#fldtaxcode').attr('disabled')){
                $('#fldtaxcode').removeAttr('disabled');
            }
        }else{
            $('#fldtaxcode').prop('selectedIndex',0).change();
            $('#fldtaxcode').attr('disabled','disabled');
        }
    });

    $(document).on('change','#fldsurgtype',function(){
        $('#fldsurgcode').val($(this).find(':selected').attr('rel1'));
    });

    $('#surgOrthoForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $('#sutureForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $('#brandForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>
