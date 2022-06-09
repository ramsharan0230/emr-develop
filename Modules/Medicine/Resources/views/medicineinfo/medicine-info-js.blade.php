<script>

    var dosageforms = <?php echo json_encode($dosageforms); ?>;

    $(document).on('keyup','.search-input',function() {
        var $rows = $('#medicineListingTable tr');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    function select2loading() {
        $('.select2genericname').select2({
            'placeholder' : 'Select Generic Name'
        });
        $('.select2DosageForms').select2({
            placeholder : 'Select Dosage'
        });
    }

    select2loading();

    $('#genericnameaddaddbutton').click(function() {
        var genericname = $('#genericnamefield').val();
        if(genericname != '') {
            $.ajax({
                type : 'post',
                url  : '{{ route('medicines.addgeneric') }}',
                dataType : 'json',
                data : {
                '_token': '{{ csrf_token() }}',
                'fldcodename': genericname,
                },
                success: function (res) {
                    showAlert(res.message);
                    if(res.message == 'Generic Name added successfully.') {
                        $('#genericnamefield').val('');
                        var deleteroutename = "{{ url('/medicines/deletegeneric') }}/"+encodeURIComponent(genericname);
                        $('#genericnamelistingmodal').append('<li class="generic-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="generic_item" data-href="'+deleteroutename+'" data-id="'+genericname+'">'+genericname+'</li>');
                        $('.select2genericname').append('<option value="'+res.fldcodename+'" data-id="'+res.fldcodename+'">'+res.fldcodename+'</option>');
                        select2loading();
                    }
                }
            });
        } else {
            alert('Generic Name is required');
        }
    });

    $('#genericnamelistingmodal').on('click', '.generic_item', function() {
        $('#genericnamefield').val($(this).html());
        $('#genericnametobedeletedroute').val($(this).data('href'));
        $('#genericidtobedeleted').val($(this).data('id'));
    });

    $('#genericnamedeletebutton').click(function() {
        var deletegenericroute = $('#genericnametobedeletedroute').val();
        var deletegenericid = $('#genericidtobedeleted').val();
        if(deletegenericroute == '') {
            alert('no generic info selected, please select the generic info.');
        }
        if(deletegenericroute != '') {
            var really = confirm("You really want to delete this Generic Info?");
            if(!really) {
                return false
            } else {
                $.ajax({
                    type : 'delete',
                    url : deletegenericroute,
                    dataType : 'json',
                    data : {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        if(res.message == 'error') {
                            showAlert(res.errormessage);
                        } else if(res.message == 'success') {
                            showAlert(res.successmessage);
                            $("#genericnamelistingmodal").find(`[data-href='${deletegenericroute}']`).parent().remove();
                            $(".select2genericname").find(`[data-id='${deletegenericid}']`).remove();
                            $('#genericnametobedeletedroute').val('');
                            $('#genericidtobedeleted').val('');
                            $('#genericnamefield').val('');
                        }
                    }
                });
            }
        }
    });

    $('#med_category_modal').on('hidden.bs.modal', function () {
        $('#dosageformfield').val('');
        $('#dosagelistingmodal').empty();
        $.each(dosageforms, function(i, dosage) {
            var dyndeleteroutename = "{{ url('/medicines/deletedosageform') }}/"+dosage.fldid;
            $('#dosagelistingmodal').append('<li class="dosage-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="'+dyndeleteroutename+'" data-id="'+dosage.fldid+'">'+dosage.flforms+'</li>');
        });
    })

    $('#dosageaddbutton').click(function() {
        var dosagename = $('#dosageformfield').val();
        if(dosagename != '') {
            $.ajax({
                type : 'post',
                url  : '{{ route("medicines.adddosageform") }}',
                dataType : 'json',
                data : {
                    '_token': '{{ csrf_token() }}',
                    'flforms': dosagename,
                },
                success: function (res) {
                    showAlert(res.message);
                    if(res.message == 'Dosage Form added successfully.') {
                        $('#dosageformfield').val('');
                        var deleteroutename = "{{ url('/medicines/deletedosageform') }}/"+res.fldid;
                        dosageforms = res.dosageforms;
                        $.each(res.dosageforms, function(i, dosage) {
                            var dyndeleteroutename = "{{ url('/medicines/deletedosageform') }}/"+dosage.fldid;
                            $('#dosagelistingmodal').append('<li class="dosage-list" style="border: 1px solid #ced4da;"><a href="javascript:void(0)" class="dosage_item" data-href="'+dyndeleteroutename+'" data-id="'+dosage.fldid+'">'+dosage.flforms+'</li>');
                        });
                        $('.select2DosageForms').append('<option value="'+res.flforms+'" data-id="'+res.fldid+'">'+res.flforms+'</option>');
                        select2loading();
                    }
                }
            });
        } else {
            alert('Dosage Form Name is required');
        }
    });

    $('#dosagelistingmodal').on('click', '.dosage_item', function() {
        $('#dosagetobedeletedroute').val($(this).data('href'));
        $('#dosageidtobedeleted').val($(this).data('id'));
        $('#dosageformfield').val($(this).html());
    });

    $('#dosagedeletebutton').click(function() {
        var deletedosageroute = $('#dosagetobedeletedroute').val();
        var dosageidtobedeleted = $('#dosageidtobedeleted').val();
        if(deletedosageroute == '') {
            alert('no Dosage selected, please select the Dosage.');
        }
        if(deletedosageroute != '') {
            var really = confirm("You really want to delete this Dosage?");
            if(!really) {
                return false
            } else {
                $.ajax({
                    type : 'delete',
                    url : deletedosageroute,
                    dataType : 'json',
                    data : {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        if(res.message == 'success') {
                            showAlert(res.successmessage);
                            $("#dosagelistingmodal").find(`[data-href='${deletedosageroute}']`).parent().remove();
                            $(".select2DosageForms").find(`[data-id='${dosageidtobedeleted}']`).remove();
                            $('#dosagetobedeletedroute').val('');
                            $('#categoryidtobedeleted').val('');
                            $('#dosageformfield').val('');
                            select2loading();
                        } else if(res.message == 'error') {
                            showAlert(res.errormessage);
                        }
                    }
                });
            }
        }
    });

    $(document).on('change','#genericCodeSelect',function(){
        var selectedGenericName = $(this).val();
        $('#brand_flddrug').val("");
        $('#labelling_flddrug').val("");
        $('#medicine_fldcodename').val(selectedGenericName);
        $('#brand_fldcodename').val(selectedGenericName);
        $('#flddrug').val(selectedGenericName);
        $('#brand_tab').removeAttr("data-toggle");
        $('#label_tab').removeAttr("data-toggle");
        $('.brand-table-list').html("");
        if(selectedGenericName != ""){
            $.ajax({
                url: '{{ route("medicines.medicineinfo.by.generic") }}',
                type: 'get',
                dataType: 'json',
                data: {
                    'fldcodename' : selectedGenericName,
                },
                success: function(res) {
                    if(!res.status){
                        alert(res.errormessage);
                    } else if(res.status == true) {
                        clearMedicineFields();
                        clearBrandFields();
                        clearLabelingFields();
                        toggleActiveTabs("medicine_tab");
                        $('#medicinelistingtable').html(res.html);
                    }
                }
            });
        }
    });

    $('#medicineForm').on('submit', function(event){
    // $(document).on('click','#medicineSave',function(event){
        event.preventDefault();
        if($('#genericCodeSelect').val() != ""){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url:"{{ route('medicines.medicineinfo.adddrug') }}",
                method:"POST",
                data: new FormData($('#medicineForm')[0]),
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                success:function(data){
                    if(data.status){
                        $('#medicinelistingtable').html(data.html);
                        $('#brand_flddrug').val(data.drugname);
                        $('#brand_fldcodename').val($('#genericCodeSelect').val());
                        toggleActiveTabs("brand_tab");
                        // toggleActiveTabs("medicine_tab");
                        showAlert(data.message);
                    }else{
                        showAlert("Something went wrong!!", 'Error');
                    }
                }
            });
        }else{
            alert("Please select generic name first!");
        }
    });

    function toggleActiveTabs(tabName){
        var navItems = ["medicine_tab","brand_tab","label_tab"];
        var tabPanes = ["medicine","brand","label"];
        var navTabs = {"medicine_tab": "medicine", "brand_tab": "brand", "label_tab": "label"};
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

    $(document).on('click','.selectmedicine',function(){
        var drugName = $(this).attr('data-drug');
        jQuery('.med-brand-ul').each(function() {
            var curr = $(this).find('.medicine-brand');
            if(drugName != curr.attr('data-drug')){
                if(curr.hasClass('show')){
                    $(this).css('display','none');
                    curr.removeClass('show');
                }
            }
        });
        var medicineBrand = $(this).closest('.medicine').find('.med-brand-ul');
        jQuery(medicineBrand).each(function() {
            var current = $(this).find('.medicine-brand');
            if(current.hasClass('show')){
                $(this).css('display','none');
                current.removeClass('show');
            }else{
                $(this).css('display','inline-block');
                current.addClass('show');
            }
        });
    });

    $(document).on('click','.editmedicine',function(){
        var drugName = $(this).attr('data-drug');
        $.ajax({
            url: '{{ route("medicines.medicineinfo.drug.details") }}',
            type: 'get',
            dataType: 'json',
            data: {
                'drugName' : drugName,
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,"error");
                } else if(res.status == true) {
                    toggleActiveTabs("medicine_tab");
                    clearBrandFields();
                    $('#brand_flddrug').val(res.drugDetails.flddrug);
                    $('#labelling_flddrug').val(res.drugDetails.flddrug);
                    $("#medicineDosage").val(res.drugDetails.fldroute).trigger('change');
                    $("#fldstrength").val(res.drugDetails.fldstrength);
                    $("#fldstrunit").val(res.drugDetails.fldstrunit);
                    $("#fldciyear").val(res.drugDetails.fldciyear);
                    $("#fldreference").val(res.drugDetails.fldreference);
                    $("#fldhelppage").val(res.drugDetails.fldhelppage);
                    $('.brand-table-list').html(res.brandHtml);

                    if(res.drugDetails.label.length > 0){
                        CKEDITOR.instances['fldopinfo'].setData(res.drugDetails.label[0].fldopinfo);
                        CKEDITOR.instances['fldipinfo'].setData(res.drugDetails.label[0].fldipinfo);
                        CKEDITOR.instances['fldasepinfo'].setData(res.drugDetails.label[0].fldasepinfo);
                        CKEDITOR.instances['fldmedinfo'].setData(res.drugDetails.label[0].fldmedinfo);
                    }
                }
            }
        });
    });

    $(document).on('click','.deletemedicine',function(){
        if(!confirm("Delete?")){
           return false;
        }
        var fldcodename = $(this).attr('data-codename');
        var flddrug = $(this).attr('data-drug');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $.ajax({
            url: '{{ route("medicines.medicineinfo.deletedrug") }}',
            type: 'DELETE',
            dataType: 'json',
            data: {
                'fldcodename' : fldcodename,
                'flddrug' : flddrug
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,'error');
                } else if(res.status == true) {
                    $('#medicinelistingtable').html(res.html);
                    clearMedicineFields();
                    clearBrandFields();
                    clearLabelingFields();
                    $('.brand-table-list').html('');
                    toggleActiveTabs("medicine_tab");
                    showAlert(res.message);
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

    $( "#fldcccharg_val" ).focusout(function() { 
        var cccharge = $('.fldcccharge:checked').val();
        if((cccharge == "fldcccharge_percent") && ( ($("#fldcccharg_val").val()) > 99.99)){
            alert("CC Charge should not be greater than 99.99 %")
            var valChart = $("#fldcccharg_val").val('');
        }
    })

    function editBrand(current){
        var fldbrandid = current.attr('data-brandid');
        var flddrug = current.attr('data-drug');
        $.ajax({
            url: '{{ route("medicines.medicineinfo.brand.details") }}',
            type: 'get',
            dataType: 'json',
            data: {
                'fldbrandid' : fldbrandid,
                'flddrug' : flddrug
            },
            success: function(res) {
                if(!res.status){
                    alert(res.errormessage);
                } else if(res.status == true) {
                    // brand data populate
                    $("#fldbrand").val(res.brandDetails.fldbrand);
                    $("#brand_fldbrandid").val(res.brandDetails.fldbrandid);
                    $("#current_stock").val(res.brandDetails.current_stock);
                    $("#fldpackvol").val(res.brandDetails.fldpackvol);
                    $("#fldvolunit").val(res.brandDetails.fldvolunit);
                    $("#fldminqty").val(res.brandDetails.fldminqty);
                    $("#brandDosage").val(res.brandDetails.flddosageform).trigger('change');
                    $("#fldmaxqty").val(res.brandDetails.fldmaxqty);
                    $("#fldstandard").val(res.brandDetails.fldstandard);
                    $("#fldleadtime").val(res.brandDetails.fldleadtime);
                    $("#fldmanufacturer").val(res.brandDetails.fldmanufacturer);
                    $("#fldtaxcode").val(res.brandDetails.fldtaxcode).trigger('change');
                    $("#fldtaxable").val(res.brandDetails.fldtaxable).trigger('change');
                    $("#fldpreservative").val(res.brandDetails.fldpreservative);
                    $("#fldnarcotic").val(res.brandDetails.fldnarcotic).trigger('change');
                    $("#fldtabbreak").val(res.brandDetails.fldtabbreak).trigger('change');
                    $("#flddeflabel").val(res.brandDetails.flddeflabel).trigger('change');
                    $("#fldactive").val(res.brandDetails.fldactive).trigger('change');

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

                    CKEDITOR.instances['flddetail'].setData(res.brandDetails.flddetail);
                    $('.brand-table-list').html(res.brandHtml);

                    // medicine data populate
                    $('#brand_flddrug').val(res.drugDetails.flddrug);
                    $('#labelling_flddrug').val(res.drugDetails.flddrug);
                    $("#medicineDosage").val(res.drugDetails.fldroute).trigger('change');
                    $("#fldstrength").val(res.drugDetails.fldstrength);
                    $("#fldstrunit").val(res.drugDetails.fldstrunit);
                    $("#fldciyear").val(res.drugDetails.fldciyear);
                    $("#fldreference").val(res.drugDetails.fldreference);
                    $("#fldhelppage").val(res.drugDetails.fldhelppage);

                    // labelling data populate
                    if(res.drugDetails.label.length > 0){
                        CKEDITOR.instances['fldopinfo'].setData(res.drugDetails.label[0].fldopinfo);
                        CKEDITOR.instances['fldipinfo'].setData(res.drugDetails.label[0].fldipinfo);
                        CKEDITOR.instances['fldasepinfo'].setData(res.drugDetails.label[0].fldasepinfo);
                        CKEDITOR.instances['fldmedinfo'].setData(res.drugDetails.label[0].fldmedinfo);
                    }

                    toggleActiveTabs("brand_tab");
                }
            }
        });
    }

    $(document).on('click','.deletebrand',function(){
        if(!confirm("Delete?")){
           return false;
        }
        var fldbrandid = $(this).attr('data-brandid');
        var flddrug = $(this).attr('data-drug');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $.ajax({
            url: '{{ route("medicines.medicineinfo.deletebrandinfo") }}',
            type: 'DELETE',
            dataType: 'json',
            data: {
                'fldbrandid' : fldbrandid,
                'flddrug' : flddrug,
                'fldcodename' : $('#brand_fldcodename').val()
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,"error");
                } else if(res.status == true) {
                    $('.brand-table-list').html(res.brandHtml);
                    $('#medicinelistingtable').html(res.html);
                    showAlert(res.message);
                }
            }
        });
    });

    // $(document).on('click','#brandSave',function(event){
    $('#brandForm').on('submit', function(event){
        event.preventDefault();
        if($('#brand_flddrug').val() != ""){
            if(parseInt($('#fldminqty').val()) > parseInt($('#fldmaxqty').val())){
                alert('Maximum stock must be greater than minimum stock');
            }else{
                var formData = new FormData($('#brandForm')[0]);
                formData.append("flddetail", CKEDITOR.instances.flddetail.getData());
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });
                $.ajax({
                    url:"{{ route('medicines.medicineinfo.addbrandinfo') }}",
                    method:"POST",
                    data: formData,
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    success:function(data){
                        if(data.status){
                            $('.brand-table-list').html(data.brandHtml);
                            $('#medicinelistingtable').html(data.html);
                            toggleActiveTabs("label_tab");
                            clearBrandFields();
                            showAlert(data.message);
                        }else{
                            showAlert(data.message, 'Error');
                        }
                    }
                });
            }
        }else{
            alert("Please select medicine first!");
        }
    });

    $('#labellingForm').on('submit', function(event){
    // $(document).on('click','#labelingSave',function(event){
        event.preventDefault();
        if($('#labelling_flddrug').val() != ""){
            var formData = new FormData($('#labellingForm')[0]);
            formData.append("fldopinfo", CKEDITOR.instances.fldopinfo.getData());
            formData.append("fldipinfo", CKEDITOR.instances.fldipinfo.getData());
            formData.append("fldasepinfo", CKEDITOR.instances.fldasepinfo.getData());
            formData.append("fldmedinfo", CKEDITOR.instances.fldmedinfo.getData());
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url:"{{ route('medicines.medicineinfo.addlabel') }}",
                method:"POST",
                data: formData,
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                success:function(data){
                    if(data.status){
                        toggleActiveTabs("medicine_tab");
                        showAlert(data.message);
                    }else{
                        showAlert("Something went wrong!!", 'Error');
                    }
                }
            });
        }else{
            alert("Please select medicine first!");
        }
    });

    $(document).on('click','#clearMedicine',function(){
        clearMedicineFields();
    });

    $(document).on('click','#clearBrand',function(){
        clearBrandFields();
    });

    function clearMedicineFields(){
        $('#medicineForm').find("input[type=text], input[type=number]").not('input[name=flddrug]').val("");
        $('#medicineForm').find("select").prop('selectedIndex',0).change();
    }

    function clearBrandFields(){
        $('#brandForm').find("input[type=text], input[type=number]").val("");
        $('#brandForm').find("select").prop('selectedIndex',0).change();
        $('#brand_fldbrandid').val("");
        CKEDITOR.instances['flddetail'].setData("");
    }

    function clearLabelingFields(){
        CKEDITOR.instances['fldopinfo'].setData("");
        CKEDITOR.instances['fldipinfo'].setData("");
        CKEDITOR.instances['fldasepinfo'].setData("");
        CKEDITOR.instances['fldmedinfo'].setData("");
    }

    $(document).on('click','#brand_tab',function(e){
        if($('#brand_flddrug').val() != ""){
            $(this).attr("data-toggle","tab");
            $(this)[0].click();
        }else{
            $(this).removeAttr("data-toggle");
        }
    });

    $(document).on('click','#label_tab',function(e){
        if($('#labelling_flddrug').val() != ""){
            $(this).attr("data-toggle","tab");
            $(this)[0].click();
        }else{
            $(this).removeAttr("data-toggle");
        }
    });

    $(document).on('change','#showBrandLists',function(){
        if ($(this).prop("checked") == true) {
            $('#brandTableLists').css("display", "block");
        } else {
            $('#brandTableLists').css("display", "none");
        }
    });

    $('#medicineForm').on('keyup keypress', function(e) {
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

    $('#labellingForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

</script>
