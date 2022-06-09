
<style>

.qrd-wrapper{
    position:relative;
    width:100%;
    height: 50px;
    font-size: 13px;
    margin-top:0px;
}

.qrd-wrapper .box{
    padding:0 10px;
    align-items: center;
}

.qrd-wrapper .box .number{
flex:0 0 30px;
line-height: 30px;
}

.qrd-wrapper .box .content{
    padding: 0 10px;
    text-align: left;
    font-size:11px;
    line-height:16px;
}


/* .qrd-wrapper .right,.qrd-wrapper .left{
    align-items: center;
    padding:0 4px;
} */

.qrd-wrapper .left{
    width:50%;
    height:100%;
    position: absolute;
    left:0;
    display:flex;
    background-color: #d12127;
    color:white;
    border-top-left-radius:10px;
    border-bottom-left-radius:10px;
}

.left .number{
    background-color:white;
    color:#d12127;
}

.right .number{
    color:white;
}

.qrd-wrapper .right{
    width:50%;
    left:50%;
    height:100%;
    position:absolute;
    display:flex;
    background-color: #f7f7f7;
    z-index: 9;
    border-top-right-radius:10px;
    border-bottom-right-radius:10px;
}

.number{
    border-radius:50%;
    width:30px;
    height: 30px;
    color:black;
    text-align:center;
    line-height:40px;
    background-color:#d12127;
    /* display: flex;
    flex: 0 0 auto; */
}

.footer{
    display:flex;
    justify-content:center;
    margin-top:10px;
    margin-bottom:20px;
    align-items:center;
}

.contact ul{
    list-style: none;
    padding-left: 10px;
}

.contact ul li{
    text-align: left;
    line-height: 14px;
    font-size: 12px;
}

#file-modal .modal-sm{
    max-width:605px;

}

.modal-footer{
    display:none;
}

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

            <div class=" iq-card-block iq-card-stretch iq-card-height">
                <div class="iq-card-header text-center">
                    <div class="iq-header-title ">
                        <h5 class="card-title " style="margin-bottom:0;">We accept</h5>
                        <img src="{{asset('new/images/fonepay.png')}}"  alt="government-logo" style="width:115px;" class="mb-1"/>
                       
                    </div>
                </div>
                <div class="ext-center" style="">
                    <div class="text-center">
                        <div style="margin:10px 20px;">
                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG(json_decode($response_qr)->qrMessage, 'QRCODE') }}" alt="barcode" style="width:100%;max-width:210px;height:auto;text-align:center;"/>
                        <input type="hidden" name="encounterId" id="encounterId" value="{{$encounterid}}">
    					<input type="hidden" name="form_name" id="form_name" value="{{$form}}">
                        </div>
                        <h5 class="my-2">Cogent Health Pvt ltd </h5>
                        <!-- <p>Terminal : <span>121312121211</span></p> -->

                        <div class="qrd-wrapper">
                            <div class="box left">
                            <div class="number">1</div>
                            <div class="content"> Open your Fonepay member mobile esewa app</div>
                            </div>

                            <div class="box right">
                            <div class="number">2</div>
                            <div class="content">Scan this code,confirm the payment details and make quick payment</div>
                            </div>
                        </div>

                        <div class="footer">
                        <div >
                        <img src="{{asset('new/images/esewa.png')}}"  alt="government-logo" style="width:115px; border-right: 1px solid #ccc;" class="mb-1 pr-1"/>
                        </div>
                        <div class="contact">
                            <ul>
                                <li> Phone:<span>01-42454545</span></li>
                                <li> Email:<span>cogenthealth.com</span></li>
                            </ul>
                       
                        </div>
                        </div>

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function () {
		let text = '{{json_decode($response_qr)->thirdpartyQrWebSocketUrl}}';
		const myArray = text.split(":");
		var host = "{{ json_decode($response_qr)->thirdpartyQrWebSocketUrl }}"; 
	    var socket = new WebSocket(host);
	    var formname = $('#form_name').val();
	    socket.onopen = function (msg) {
            if (this.readyState != 1)
            {
                reconnect();
            }
        };
        socket.onmessage = function (msg) {
          var responsedata = JSON.parse(msg.data);
          var responsemessage = JSON.parse(responsedata.transactionStatus);
          if(responsemessage.message === 'RES000' && responsemessage.success == true){
          	$.ajax({
	            url: "{{ route('billing.save.convergentpayment') }}",
	            type: "POST",
	            data: { "form":$('#form_name').val(),"encounterid": $('#encounterId').val(),"response":msg.data, "_token": "{{ csrf_token() }}" },
	            success: function (data) {
	            	if(data.success === true){
	            		showAlert('Payment Success', 'success');
	            		$('#file-modal').modal('hide');
	            		if(formname == 'Cashier Form'){
	            			$('.js-fonepaylog-id-hidden').val(data.fonepaylogId);
	            			$('#js-billing-save-btn').trigger('click');
	            		}
	            		if(formname == 'Registration Form'){
	            			$('.js-fonepaylog-id-hidden').val(data.fonepaylogId);
	            			var activeForm = $('div.tab-pane.fade.active.show');
	            			var regtype = $(activeForm).find('.js-registration-regtype-hidden').val()
	            			if(regtype === 'New Registration'){
	            				 $("#regsitrationForm").submit();
            				}else{
            					$("#oldRegistrationForm").submit();
	            			}
	            			// $('.js-registrationform-submit-btn').trigger('click');
	            		}
	            		if(formname == 'Dispensing Form'){
	            			$('.js-fonepaylog-id-hidden').val(data.fonepaylogId);
	            			$('#js-dispensing-print-btn').trigger('click');
	            		}

	            		if(formname == 'Deposit Form'){
	            			$('.js-fonepaylog-id-hidden').val(data.fonepaylogId);
	            			$('#js-deposit-form-submit-button').trigger('click');
	            		}

	            		if(formname == 'Discharge Clearance Form'){
	            			$('.js-fonepaylog-id-hidden').val(data.fonepaylogId);
	            			$('#payment-save-done').trigger('click');
	            		}

	            		if(formname == 'Deposit Clearance Form'){
	            			$('.js-fonepaylog-id-hidden').val(data.fonepaylogId);
	            			$('#payment-save-done').trigger('click');
	            		}

	            		if(formname == 'Credit Clearance Form'){
	            			$('.js-fonepaylog-id-hidden').val(data.fonepaylogId);
	            			$('#js-deposit-form-submit-button-credit-clerance').trigger('click');
	            		}
	            		
	            	}else{
	            		showAlert('Something went wrong. Please try again !!', 'error');
	            	}
	                
	            }
	        });
          }
          	
        };
        socket.onclose = function (msg) {
           alert("Fonepay Session Expired");
        };
	});
	
</script>