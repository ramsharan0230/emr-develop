<script>
    CKEDITOR.replace('flddetail',
            {
                height: '200px',
            });

    $(document).on('change','#showBrandLists',function(){
        if ($(this).prop("checked") == true) {
            $('#brandTableLists').css("display", "block");
        } else {
            $('#brandTableLists').css("display", "none");
        }
    });

    $(document).on('change','#selectExtraItems',function(){
        var fldextraid = $(this).val();
        if(fldextraid != ""){
            $.ajax({
                url: '{{ route("itemNameInfo") }}',
                type: 'get',
                dataType: 'json',
                data: {
                    'fldextraid' : fldextraid,
                },
                success: function(res) {
                    if(!res.status){
                        showAlert(res.message,"error");
                    } else if(res.status == true) {
                        clearItemNameFields();
                        clearBrandFields();
                        $('#main-tab').css('display','flex');
                        $('.tab-content').css('display','block');
                        $('#extraItemFldId').val(res.extras.fldid);
                        $('#fldextraid').val(res.extras.fldextraid);
                        $('#extraitemlistingtable').html(res.data.html);
                        $('.brand-table-list').html(res.data.brandHtml);
                        $('#extra_id').val(fldextraid);
                    }
                }
            });
        }else{
            $('#main-tab').css('display','none');
            $('.tab-content').css('display','none');
            $('#extraitemlistingtable').html("");
        }
    });

    $(document).on('click', '.row-links td:not(.deletebrand)', function(){
        editBrand($(this).closest('.row-links'));
    });

    $(document).on('click','.editbrand',function(){
        editBrand($(this));
    });

    function editBrand(current){
        var fldbrandid = current.attr('data-brandid');
        $('#brandid').val(fldbrandid);
        $.ajax({
            url: '{{ route("getBrandDetails") }}',
            type: 'get',
            dataType: 'json',
            data: {
                'fldbrandid' : fldbrandid
            },
            success: function(res) {
                if(!res.status){
                    alert(res.message);
                } else if(res.status == true) {
                    $('#fldbrand').val(res.brandDetail.fldbrand);
                    $('#fldstandard').val(res.brandDetail.fldstandard).change();
                    $('#fldpackvol').val(res.brandDetail.fldpackvol);
                    $('#fldvolunit').val(res.brandDetail.fldvolunit);
                    $('#fldmanufacturer').val(res.brandDetail.fldmanufacturer);
                    $('#fldminqty').val(res.brandDetail.fldminqty);
                    $('#fldmaxqty').val(res.brandDetail.fldmaxqty);
                    $('#flddepart').val(res.brandDetail.flddepart).change();
                    $('#fldleadtime').val(res.brandDetail.fldleadtime);
                    $('#fldactive').val(res.brandDetail.fldactive).change();
                    $('#fldtaxable').val(res.brandDetail.fldtaxable).change();
                    $('#fldtaxcode').val(res.brandDetail.fldtaxcode).change();
                    // newd
                    console.log("fldmrp:", res.brandDetail)
                    // $("#fldmrp").val(res.brandDetail.fldmrp);
                    
                    if(res.brandDetail.fldcccharge=="fldcccharge_amt")
                        $('input:radio[name="fldcccharge"]').filter('[value="fldcccharge_amt"]').attr('checked', true);
                    else
                        $('input:radio[name="fldcccharge"]').filter('[value="fldcccharge_percent"]').attr('checked', true);
                    
                    if(res.brandDetail.fldinsurance ==1)
                        $('input:radio[name="fldinsurance"]').filter('[value=1]').attr('checked', true);
                    else
                        $('input:radio[name="fldinsurance"]').filter('[value=0]').attr('checked', true);

                    if(res.brandDetail.fldrefundable ==1)
                        $('input:radio[name="fldrefundable"]').filter('[value=1]').attr('checked', true);
                    else
                        $('input:radio[name="fldrefundable"]').filter('[value=0]').attr('checked', true);
                      
                    if(res.brandDetail.flddiscountable_item ==1)
                        $('input:radio[name="flddiscountable_item"]').filter('[value=1]').attr('checked', true);
                    else
                        $('input:radio[name="flddiscountable_item"]').filter('[value=0]').attr('checked', true);
                        
                    $("#fldcccharg_val").val(res.brandDetail.fldcccharg_val);
                    $("#fldmrp").val(res.brandDetail.fldmrp);
                    // end neww
                    CKEDITOR.instances['flddetail'].setData(res.brandDetail.flddetail);
                }
            }
        });
    }

    $( "#fldcccharg_val" ).focusout(function() { 
        var cccharge = $('.fldcccharge:checked').val();
        if((cccharge == "fldcccharge_percent") && ( ($("#fldcccharg_val").val()) > 99.99)){
            alert("CC Charge should not be greater than 99.99 %")
            var valChart = $("#fldcccharg_val").val('');
        }
    })

    $(document).on('click','#extraItemSave',function(e){
        e.preventDefault();
        var item_name = $('#fldextraid').val();
        if(fldextraid != ""){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url: '{{ route("insert.item.name.variable") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    'item_name' : item_name
                },
                success: function(res) {
                    if(!res.status){
                        showAlert(res.message,'error');
                    } else if(res.status == true) {
                        var newOption = new Option(item_name, item_name, false, false);
                        $('#selectExtraItems').append(newOption);
                        $('#extraItemFldId').val(res.insertId);
                        showAlert(res.message);
                    }
                }
            });
        }else{
            alert("Item name is required.");
        }
    });

    $(document).on('click','#extraItemDelete', function(){
        if(!confirm("Delete?")){
            return false;
        }
        var extraItemFldId = $('#extraItemFldId').val();
        var item_name = $('#fldextraid').val();
        if(extraItemFldId != ""){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });
            $.ajax({
                url: '{{ route("delete.item.name.variable") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    'fldid' : extraItemFldId
                },
                success: function(res) {
                    if(!res.status){
                        showAlert(res.message,"error");
                    } else if(res.status == true) {
                        clearItemNameFields();
                        $('#extraitemlistingtable').html("");
                        $("#selectExtraItems option[value='"+item_name+"']").remove();
                        $('#selectExtraItems').val('selectedIndex',0);
                        showAlert(res.message);
                    }
                }
            });
        }else{
            showAlert('Please select Item First!','error')
        }
    });

    // $(document).on('click','#brandSave',function(event){
    $('#brandForm').on('submit', function(event){
        event.preventDefault();
        if($('#extra_id').val() != ""){
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
                    url:"{{ route('insert.extra.item') }}",
                    method:"POST",
                    data: formData,
                    contentType: false,
                    cache:false,
                    processData: false,
                    dataType:"json",
                    success:function(res){
                        if(res.status){
                            $('#extraitemlistingtable').html(res.data.html);
                            $('.brand-table-list').html(res.data.brandHtml);
                            clearBrandFields();
                            $('#extra_id').val($('#selectExtraItems').val());
                            showAlert(res.message);
                        }else{
                            showAlert(res.message, 'Error');
                        }
                    }
                });
            }
        }else{
            alert("Please select item first!");
        }
    });

    $(document).on('click','.deletebrand', function(){
        if(!confirm("Delete?")){
            return false;
        }
        var fldbrandid = $(this).attr('data-brandid');
        var fldextraid = $('#selectExtraItems').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });
        $.ajax({
            url: '{{ route("delete.extra.item") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                'fldbrandid' : fldbrandid,
                'fldextraid' : fldextraid
            },
            success: function(res) {
                if(!res.status){
                    showAlert(res.message,"error");
                } else if(res.status == true) {
                    $('#extraitemlistingtable').html(res.data.html);
                    $('.brand-table-list').html(res.data.brandHtml);
                    showAlert(res.message);
                }
            }
        });
    });

    $(document).on('click','#extraItemClear',function(){
        clearItemNameFields();
    });

    function clearItemNameFields(){
        $('#fldextraid').val("");
        $('#extraItemFldId').val("");
    }

    function clearBrandFields(){
        $('#brandid').val("");
        $('#brandForm').find("input[type=text], input[type=number]").val("");
        $('#extra_id').val("");
        $('#brandForm').find("select").prop('selectedIndex',0).change();
        CKEDITOR.instances['flddetail'].setData("");
    }

    $(document).on('click','#clearBrand',function(){
        clearBrandFields();
    });

    $('#brandForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>
