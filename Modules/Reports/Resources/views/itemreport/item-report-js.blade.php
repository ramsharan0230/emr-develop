<script type="text/javascript">
    $(window).ready(function () {
        $('#to_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });
        $('#from_date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 // Options | Number of years to show
        });

    })

    $(document).ready(function() {
        $("input:radio[name='itemRadio']").click(function(e) {
            const radio = $('input:radio[name=itemRadio]:checked').val();
            $('#selectedItem').val("");
            if(radio == 'packages') {
                $("#item-listing-table").hide();
                $("#package-listing-table").show();
                $('#package-listing-table').find('.select_td').removeClass("select_td");
            } else {
                if(radio == 'select_item') $('#item-listing-table').find('.select_td').removeClass("select_td");
                else $('#item-listing-table').find('td').addClass("select_td");
                $("#package-listing-table").hide();
                $("#item-listing-table").show();
            }
        });
    });

    $("#entryDate").unbind('click').click( function(){
        return false;
    });

    $(document).on('keyup', '.search-input', function () {
        var $rows = $('#item-table tr');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $(document).ready(function () {
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchDepositDetail(page);
        });
    });

    function exportDepositReport() {
        var urlReport = baseUrl + "/depositForm/deposit-report/pdf?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&lastStatus=" + $('#lastStatus').val() + "&deposit=" + $('#depositSelect').val();
        window.open(urlReport, '_blank');
    }

    function exportDepositReportExcel() {
        var urlReport = baseUrl + "/depositForm/deposit-report/excel?from_date=" + $('#from_date').val() + "&to_date=" + $('#to_date').val() + "&lastStatus=" + $('#lastStatus').val() + "&deposit=" + $('#depositSelect').val();
        window.open(urlReport);
    }

    function searchDepositDetail(page) {
        var url = "{{route('searchDepositDetail')}}";
        $.ajax({
            url: url + "?page=" + page,
            type: "GET",
            data: $("#deposit_filter_data").serialize(),
            success: function (response) {
                if (response.data.status) {
                    $('#deposit_result').html(response.data.html)
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function loadData() {
        $('#to_date_eng').val(BS2AD($('#to_date').val()));
        $('#from_date_eng').val(BS2AD($('#from_date').val()));
        var url = "{{route('item.report.loaddata')}}";
        $.ajax({
            url: url,
            type: "GET",
            data: {
                category: $('#category').val(),
                from_date: $('#from_date_eng').val(),
                to_date: $('#to_date_eng').val(),
                billingmode: $('#billing_mode').val()
            },
            success: function (response) {
                if (response.data.status) {
                    $('#item-listing-table').html(response.data.itemHtml);
                    $('#package-listing-table').html(response.data.packageHtml);
                } else {
                    showAlert("Something went wrong...", "error");
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log(xhr);
            }
        });
    }

    function getRefreshData(page = 1) {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var url = "{{route('item.report.refreshdata')}}";
            $.ajax({
                url: url + "?page=" + page,
                type: "POST",
                data: {
                    category: $('#category').val(),
                    billingmode: $('#billing_mode').val(),
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val(),
                    comp: $('#comp').val(),
                    departments: $('#departments').val(),
                    selectedItem: $('#selectedItem').val(),
                    dateType: $("input[name='selectDate']:checked").val(),
                    itemRadio: $("input[name='itemRadio']:checked").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.data.status) {
                        if(response.data.type == 'packages') {
                            if($('#grid table thead tr').find('th').eq(1).html() != 'Package Name') {
                                $('#grid table thead tr').find('th').eq(0).after('<th>Package Name</th>');
                            }
                        } else {
                            console.log($('#grid table thead tr').find('th').eq(1).html());
                            if($('#grid table thead tr').find('th').eq(1).html() == 'Package Name') {
                                $('#grid table thead tr').find('th').eq(1).remove();
                            }
                        }
                        $('#item_result').html(response.data.html);
                    } else {
                        showAlert("Something went wrong...", "error");
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        } else {
            showAlert("Please select data first!!!");
        }
    }

    $(document).on('click', '.item-td', function () {
        const radio = $('input:radio[name=itemRadio]:checked').val();
        if(radio == 'select_item' || radio == 'packages') {
            const $type = $(this).parent().parent().attr('id');
            var selectedItem = $(this).attr('data-itemname');
            $('#selectedItem').val(selectedItem);
            $('#'+$type).find('.select_td').removeClass("select_td");
            $(this).addClass('select_td');
        }
    });

    $(document).ready(function () {
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getRefreshData(page);
        });
    });

    function exportItemReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/export-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function excelItemReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/export-excel?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDatewiseReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/datewise-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportCatWiseItemReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/categorywise-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportItemParticularReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/particularwise-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDetailReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/item-details-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDatesReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/item-date-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportVisitsReport() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/item-visits-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function openPatientModal() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            $('#patientModal').modal('show');
        }
    }

    $(document).on('click', '#submitModal', function () {
        var cut_off_amount = $('#cut_off_amount').val();
        if (cut_off_amount != "") {
            $('#cut_off_amount').val("");
            $('#patientModal').modal('hide');
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/item-cut-off-amount-pdf?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'] + "&cut_off_amount=" + cut_off_amount;
            window.open(urlReport, '_blank');
        }
    });

    function commonRequestData() {
        var category = $('#category').val();
        var billingmode = $('#billing_mode').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var comp = $('#comp').val();
        var departments = $('#departments').val();
        var selectedItem = $('#selectedItem').val();
        var dateType = $("input[name='selectDate']:checked").val();
        var itemRadio = $("input[name='itemRadio']:checked").val();
        return {
            category: category,
            billingmode: billingmode,
            from_date: from_date,
            to_date: to_date,
            comp: comp,
            departments: departments,
            selectedItem: selectedItem,
            dateType: dateType,
            itemRadio: itemRadio
        }
    }

    function exportDatewiseExcel() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/datewise-excel?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportCatWiseItemExcel() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/categorywise-excel?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportItemParticularExcel() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/particularwise-excel?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDetailExcel() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/item-details-excel?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportDatesExcel() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/item-date-excel?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    function exportVisitsExcel() {
        if ($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items") {
            var data = commonRequestData();
            var urlReport = baseUrl + "/mainmenu/item-report/item-visits-excel?category=" + data['category'] + "&billingmode=" + data['billingmode'] + "&from_date=" + data['from_date'] + "&to_date=" + data['to_date'] + "&comp=" + data['comp'] + "&departments=" + data['departments'] + "&selectedItem=" + data['selectedItem'] + "&dateType=" + data['dateType'] + "&itemRadio=" + data['itemRadio'];
            window.open(urlReport, '_blank');
        }
    }

    

</script>
