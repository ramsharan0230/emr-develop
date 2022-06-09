<!DOCTYPE html>
<html>
<head>
	<title>Dispense Report</title>
</head>
<body style="width: 60%;">
	<div>
		<div style="width: 30%;float: left;">{{ $patientInfo->patientInfo->fldfullname }}</div>
		<div style="width: 30%;float: left;">{{ $patientInfo->fldcurrlocat }}</div>
		<div style="width: 30%;float: left;">{{ $patientInfo->fldencounterval }}</div>
	</div>
	<div style="font-weight: 600;">
		<div style="width: 80%;float: left;">{{ $medicine->flditem }}</div>
		<div style="width: 20%;float: left;">[{{ $medicine->fldqtydisp }}]</div>
	</div>
	<br><br><br><br><br><br>
	<div style="text-align: center;">BID</div>
	<div>{{ $medicine->flddose }} ml, Every {{ ceil(24/$medicine->fldfreq )}} Hour Diffrence {{ $medicine->flddays }} Day</div>
	<div>
		<div style="width: 30%;float: left;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</div>
		<div style="width: 30%;float: left;">{{ date('Y-m-d') }}</div>
		<div style="width: 30%;float: left;">{{ $medicine->fldroute }}</div>
	</div>
</body>
</html>