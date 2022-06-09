<script>
    $(document).on('keyup','.search-input',function() {
        var $rows = $('#group-table tr');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $(document).on('keyup','#searchItemListing',function() {
        var $rows = $('#item-list tr');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $('#from_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,

    });

    $('#to_date').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,

    });

    function searchDepositDetail(page){
        var url = "{{route('searchDepositDetail')}}";
        $.ajax({
            url: url+"?page="+page,
            type: "GET",
            data:  $("#deposit_filter_data").serialize(),
            success: function(response) {
                if(response.data.status){
                    $('#deposit_result').html(response.data.html)
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function createGroup(){
        var url = "{{route('group.getGroups')}}";
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if(response.data.status){
                    $("#groupOptions").empty().append(new Option("", ""));
                    $.each(response.data.groups, function( index, value ) {
                        $("#groupOptions").append(new Option(value.fldgroup, value.fldgroup));
                    });
                    $('#groupModal').modal('show');
                }else{
                    showAlert("Something went wrong...","error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function loadGroup(){
        var url = "{{route('group.getGroupData')}}";
        $.ajax({
            url: url,
            type: "GET",
            data:  {
                        group_name: $('#group_name').val()
                    },
            success: function(response) {
                if(response.data.status){
                    $('#group-listing-table').html(response.data.html);
                }else{
                    showAlert("Something went wrong...","error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function addGroup(){
        if($('#group_name').val() != ""){
            var url = "{{route('group.getGroupSelectedItems')}}";
            $.ajax({
                url: url,
                type: "GET",
                data:  {
                            groupName: $('#group_name').val(),
                        },
                success: function(response) {
                    if(response.data.status){
                        $('#groupName').val($('#group_name').val());
                        $('#item-selected-listing-table').html(response.data.html);
                        $('#addGroupModal').modal('show');
                    }else{
                        showAlert("Something went wrong...","error");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }

    $(document).on('change','#groupcategory',function(){
        var category = $(this).val();
        var url = "{{route('group.getGroupCategoryData')}}";
        $.ajax({
            url: url,
            type: "GET",
            data:  {
                        category: category,
                    },
            success: function(response) {
                if(response.data.status){
                    $('#item-listing-table').html(response.data.html);
                }else{
                    showAlert("Something went wrong...","error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });

    function loadData(){
        var url = "{{route('group.getGroups')}}";
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if(response.data.status){
                    $("#listing-table").html("");
                    $.each(response.data.groups, function( index, value ) {
                        $("#listing-table").append("<tr><td data-item='"+value.fldgroup+"' class='item-td'>"+value.fldgroup+"</td></tr>");
                    });
                }else{
                    showAlert("Something went wrong...","error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    $('#item-listing-table').on('click','.item-td',function(){
        // var selectedItem = $(this).attr('data-itemname');
        // $('#selectedItem').val(selectedItem);
        if($(this).hasClass('select_td')){
            $(this).removeClass("select_td");
        }else{
            $(this).addClass('select_td');
        }
    });

    $('#listing-table').on('click','.item-td',function(){
        var selectedItem = $(this).attr('data-item');
        $('#selectedItem').val(selectedItem);
        $('#listing-table').find('.select_td').removeClass("select_td");
        $(this).addClass('select_td');
    });

    $( document ).ready(function() {
        $(document).on('click', '.pagination a', function(event){
          event.preventDefault();
          var page = $(this).attr('href').split('page=')[1];
          getRefreshData(page);
         });
    });

    $(document).on('change','#selectAllItemLists',function(){
        if($(this).is(":checked")){
            $.each($('#item-listing-table tr'), function(i, option) {
                if(!$(option).find('td').hasClass('select_td')){
                    $(option).find('td').addClass('select_td');
                }
            });
        }else{
            $.each($('#item-listing-table tr'), function(i, option) {
                if($(option).find('td').hasClass('select_td')){
                    $(option).find('td').removeClass('select_td');
                }
            });
        }
    });

    $(document).on('click','.selectItemName',function(){
        var selectedItemArray = [];
        $("#item-listing-table .select_td").each(function() {
            selectedItemArray.push($(this).attr('data-itemname'));
        });
        if(selectedItemArray.length > 0){
            var url = "{{route('group.selectGroupItemname')}}";
            $.ajax({
                url: url,
                type: "POST",
                data:  {
                            selectedItemArray: selectedItemArray,
                            groupName: $('#groupName').val(),
                            groupcategory: $('#groupcategory').val(),
                            _token: "{{ csrf_token() }}"
                        },
                success: function(response) {
                    if(response.data.status){
                        $('#item-selected-listing-table').append(response.data.html);
                        selectedItemArray.forEach(function(item) {
                            $('#item-listing-table').find('td[data-itemname="'+item+'"]').closest('tr').remove();
                        });
                    }else{
                        showAlert("Something went wrong...","error");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    });

    function groupReport(){
        var urlReport = baseUrl + "/mainmenu/group-report/get-report";
        window.open(urlReport, '_blank');
    }

    function getRefreshData(page){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var url = "{{route('group.getRefreshedData')}}";
            $.ajax({
                url: url,
                url: url+"?page="+page,
                type: "POST",
                data:  {
                            billingmode: $('#billing_mode').val(),
                            from_date: $('#from_date').val(),
                            to_date: $('#to_date').val(),
                            comp: $('#comp').val(),
                            selectedItem: $('#selectedItem').val(),
                            dateType: $("input[name='selectDate']:checked").val(),
                            itemRadio: $("input[name='itemRadio']:checked").val(),
                            _token: "{{ csrf_token() }}"
                        },
                success: function(response) {
                    if(response.data.status){
                        $('#table_result').html(response.data.html);
                    }else{
                        showAlert("Something went wrong...","error");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }

    function exportItemReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportSummaryReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-summary-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDatewiseReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-datewise-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportCatWiseItemReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-categorywise-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportItemParticularReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-particular-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDetailReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-detail-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDatesReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-dates-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportPatientReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-patient-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportVisitsReport(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-visits-report?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function commonRequestData(){
        var billingmode = $('#billing_mode').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var comp = $('#comp').val();
        var selectedItem = $('#selectedItem').val();
        var dateType = $("input[name='selectDate']:checked").val();
        var itemRadio = $("input[name='itemRadio']:checked").val();
        return {
            billingmode: billingmode,
            from_date: from_date,
            to_date: to_date,
            comp: comp,
            selectedItem: selectedItem,
            dateType: dateType,
            itemRadio: itemRadio
        }
    }

    $(document).on('click','.deleteParticular',function(){
        if(!confirm("Delete?")){
           return false;
        }
        var url = "{{route('group.removeGroupParticular')}}";
        var fldid = $(this).attr('data-fldid');
        $.ajax({
            url: url,
            type: "GET",
            data:  {
                        fldid: fldid,
                    },
            success: function(response) {
                if(response.data.status){
                    var $row = $("#group-listing-table tr[data-fldid='" + fldid + "']").closest('tr');
                    $row.remove();
                    showAlert("Group particular removed successfully.");
                }else{
                    showAlert("Something went wrong...","error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    });

    function generateCategoryExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-categorywise-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function generateItemsExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-particular-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function generateDetailsExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-detail-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function generateDatesExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-dates-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function generatePatientsExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-patient-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function generateVisitsExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-visits-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function generateSummaryExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-summary-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function generateDatewiseExcel(){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/group-report/export-datewise-excel?billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }
</script>
