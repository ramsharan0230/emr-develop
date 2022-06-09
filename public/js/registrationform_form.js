
jQuery(function($) {
    hideAll();
    setTimeout(function() {
        $(".bank-name").select2();
        $('.bank-name').next(".select2-container").hide();
    }, 1500);
    
    $('.payment_mode').on('change', function() {
        var activeForm = $('div.tab-pane.fade.active.show');
        if (this.value === "Cash") {
            hideAll();
            $(activeForm).find('.payment-save-done').show();
        } else if (this.value === "Credit") {
            hideAll();
            $(activeForm).find('.expected_date').show();
            $(activeForm).find('.payment-save-done').show();
        } else if (this.value === "Cheque") {
            hideAll();
            $('.cheque_number').show();
            // $(".payment_mode_party").show();
            /*$(".agent_list").show();*/
            $(activeForm).find('.bank-name').next(".select2-container").show();
            $(activeForm).find('.payment-save-done').show();
        } else if (this.value === "Fonepay") {
            // fonepay-button-save
            $(activeForm).find('.fonepay-button-save').show();
            $(activeForm).find('.payment-save-done').hide();
        } else if (this.value === "Others") {
            hideAll();
            $(activeForm).find('.other_reason').show();
            $(activeForm).find('.payment-save-done').show();
        }
    });

    function hideAll() {
        $('.office-name').hide();
        $('.bank-name').next(".select2-container").hide();
        $('.expected_date').hide();
        $('.cheque_number').hide();
        $('.office_name').hide();
        $('.other_reason').hide();
    }

    $(document).on('change', '.js-registration-title', function(event) {
        var title = $(this).val() || '';
        if (title != '') {
            var activeForm = $(this).closest('.tab-pane');
            title = title.trim().toLowerCase();
            $(activeForm).find('.other_salutation').hide();
            if (title == 'mr.') {
                $(activeForm).find('.js-registration-gender option').attr('selected', false);
                $(activeForm).find('.js-registration-gender option[value="Male"]').attr('selected', true);
            } else if (title == 'mrs.' || title == 'ms.') {
                $(activeForm).find('.js-registration-gender option').attr('selected', false);
                $(activeForm).find('.js-registration-gender option[value="Female"]').attr('selected', true);
            } else if (title == 'other')
                $(activeForm).find('.other_salutation').show();
    
            $(activeForm).find('.js-registration-gender').trigger('change');
        }

    });
    $(document).on("keyup", ".other_title", function(e) {
        var other = $(this).val();
        var activeForm = $(this).closest('.tab-pane');

        $(activeForm).find('.js-registration-title').append('<option value="' + other + '" selected=selected>' + other + '</option>');
    });
    $(document).on('change', '.js-registration-billing-mode', function(event) {
        var activeForm = $('div.tab-pane.fade.active.show');
        var billingmode = $(this).val();
        if (billingmode !== '') {
            $.ajax({
                method: "GET",
                data: {
                    billingmode: billingmode
                },
                dataType: "json",
                url: baseUrl + '/registrationform/getDiscmode',
            }).done(function(data) {
                $(activeForm).find('.js-registration-discount-scheme').html(data);
                $(activeForm).find('.js-registration-discount-scheme').select2();
                // $('#js-registration-discount-scheme').html(data);
            });
        }
    });

    var nepaliDateConverter = new NepaliDateConverter();
    $('.js-nepaliDatePicker').nepaliDatePicker({
        npdMonth: true,
        npdYear: true,
        onChange: function() {
            var activeForm = $('div.tab-pane.fade.active.show');
            var englishdate = ($(activeForm).find('.js-nepaliDatePicker').val()).split('-');
            englishdate = englishdate[1] + '/' + englishdate[2] + '/' + englishdate[0];
            englishdate = nepaliDateConverter.bs2ad(englishdate);
            $(activeForm).find('.expected_payment_date').val(englishdate);
        }
    });
    var date = new Date().toISOString().split("T")[0];

    var nepalidate = date.split('-');
    nepalidate = nepalidate[1] + '/' + nepalidate[2] + '/' + nepalidate[0];
    nepalidate = nepaliDateConverter.ad2bs(nepalidate);

    $('#expected_payment_date').val(date);
    $('#expected_payment_date_nepali').val(nepalidate);
});

function show1(elem) {
    var value = $(elem).find('input[name="customRadio-1"]').val();
    if (value == 'Yes')
        $('.hidedhow-register').show();
    else
        $('.hidedhow-register').hide();
}
