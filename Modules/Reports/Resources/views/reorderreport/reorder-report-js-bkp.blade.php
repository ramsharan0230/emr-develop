<script type="text/javascript">
    $(document).on('keyup','.search-input',function() {
        var $rows = $('#item-table tr');
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $( document ).ready(function() {
        $(document).on('click', '.pagination a', function(event){
          event.preventDefault(); 
          var page = $(this).attr('href').split('page=')[1];
          searchDepositDetail(page);
         });
    });

    $('#from_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,
           
       });
    $('#to_date').nepaliDatePicker({
           npdMonth: true,
           npdYear: true,
           
       });

    function loadData(){
        var url = "{{route('reorder-level.report.loaddata')}}";
        $.ajax({
            url: url,
            type: "GET",
            data:  {
                        category: $('#category').val(), 
                        billingmode: $('#billing_mode').val()
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
    }

    function getRefreshData(page){
        if($('#selectedItem').val() != "" || $('input[name="itemRadio"]:checked').val() == "all_items"){
            var url = "{{route('reorder-level.report.refreshdata')}}";
            $.ajax({
                url: url,
                url: url+"?page="+page,
                type: "POST",
                data:  {
                            category: $('#category').val(), 
                            billingmode: $('#billing_mode').val(),
                            from_date: $('#from_date').val(),
                            to_date: $('#to_date').val(),
                            comp: $('#comp').val(),
                            selectedItem: $('#selectedItem').val(),
                            itemRadio: $("input[name='itemRadio']:checked").val(),
                            _token: "{{ csrf_token() }}"
                        },
                success: function(response) {
                    if(response.data.status){
                        $('#item_result').html(response.data.html);
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

    $(document).on('click','.item-td',function(){
        var selectedItem = $(this).attr('data-itemname');
        $('#selectedItem').val(selectedItem);
        $('#item-listing-table').find('.select_td').removeClass("select_td");
        $(this).addClass('select_td');
    });

    $( document ).ready(function() {
        $(document).on('click', '.pagination a', function(event){
          event.preventDefault(); 
          var page = $(this).attr('href').split('page=')[1];
          getRefreshData(page);
         });
    });
    
</script>