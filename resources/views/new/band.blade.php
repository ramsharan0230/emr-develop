@extends('frontend.layouts.master') @section('content')
<style>
	.patDetails {
		position: absolute;
		left: 20%;
		top: 16%;
		font-size: 15px;
		font-weight: bold;
	}

	.details2 {
		position: absoulte;
		position: absolute;
		left: 20%;
		top: 26%;
		font-size: 11px;
		font-weight: bold;
	}
	.barcode {
		position: absolute;
		left: 12%;
		top: 42%;
		width: 7%;
	}
	.qrcode {
		position: absolute;
		left: 41%;
		top: 40%;
		width: 5%;
	}
	.ticket {
		height: 145px;
		width: 42%;
		background-color: #fff;
		border-radius: 15px;
	}
	.form-group-band{
		width:21%;
	}
	.details-label{
		margin-bottom:0px;
	}
</style>
<div class="container-fluid">
	<div class="card mb-5" style="background-color: #e1eff1; ">
		<img src="{{ asset('new/images/band.png')}}" alt="image" />
		<img src="{{ asset('new/images/barcode.png')}}" class="barcode" alt="image" />
		
		<label class="patDetails">ARCHANA RAI</label>
		<div class="form-group-band details2">
			<label class="details-label ">Patient ID: 1000 </label>&nbsp;
			&nbsp;
			<label class="details-label"> EncID: E1000</label>
			<label class="details-label full-width">DR. Rupak Rai</label>
			<label class="details-label ">DOB: 2020-3-05</label>&nbsp;
			&nbsp;
			<label class="details-label"> Admitted: 252525</label>
			<label class="details-label  full-width" >Age/Sex: Male/24 Years</label>
			<label class="details-label" >Room: 4</label>&nbsp;
			&nbsp;
			<label class="details-label" >Allerygy: test in case</label>
			<label class="details-label  full-width" >Disease: test in case</label>
		</div>
		<div class="qrcode">
			<img src="{{ asset('new/images/qr.png')}}" alt="image" />
		</div>
	</div>
	<div class="card mb-5" style="background-color: #e1eff1; padding: 20px;">
		<div class="ticket">
			<h6 style="text-decoration: underline;text-align:center;font-weight: bold;">ABCDFG Hospital</h6>
			<div class="form-group p-2 form-row">
				<label class="col-6">Patient NO.: 1245</label>
				<label class="col-6">Date: 2059-09-09</label>
				<label class="col-6">Name.:archana Rai</label>
				<label class="col-6">Address: kathmandu nepal</label>
				<label class="col-6">Contact: 0975734737</label>
				<label class="col-6">Gender.: Female</label>
			</div>
		</div>
	</div>
	@endsection
