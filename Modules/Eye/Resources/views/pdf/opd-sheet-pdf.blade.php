@extends('inpatient::pdf.layout.main')

@section('title')
Eye Report
@endsection

@section('report_type')
OPTHALMOLOGY OPD SHEET SAMPLE
@endsection

@section('content')
	<style type="text/css">
	    .img-eye img {
	        width: 20%;
	        height: auto;
	        position: absolute;
	        margin-bottom: 20px;
	    }
	</style>
	<div class="pdf-container">
	   <div class="row">
	   	  	<div class="table-chief" style="margin-top: 16px;">

		   	  	<table class="table content-body" style="border: 1px solid;border-collapse: collapse; width: 80%; margin: 0 auto; font-size: 18px;">
		   	  		<tbody>
		   	  			<tr>
		   	  				<td colspan="2" style="border: 1px solid; padding:5px; width: 45%;">Chief Complaints</td>
		   	  				<td  style="border: 1px solid; padding:5px;">
		   	  					@if(isset($complaint))
		   	  					@foreach($complaint as $comp)
			   	  					<li>{{$comp->flditem}} || <strong>{{$comp->fldreportquali}}</strong></li>
			   	  				@endforeach
								@endif
		   	  				</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td colspan="2" style="border: 1px solid; padding:5px;">Systemic Illness</td>
		   	  				<td  style="border: 1px solid; padding:5px;">{!! (isset($systemic_illiness->fldreportquali)) ? strip_tags($systemic_illiness->fldreportquali) : '' !!}</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td  rowspan="2" style="border: 1px solid; padding:5px;">Allergy</td>
		   	  				<td  style="border: 1px solid; padding:5px;">Drug</td>
		   	  				<td  style="border: 1px solid; padding:20px;">
		   	  					@if(isset($allergy))
		   	  					@foreach($allergy as $a)
		   	  						<li>{{ $a->fldcode}}</li>
		   	  					@endforeach
		   	  					@endif
		   	  				</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td  style="border: 1px solid; padding:5px;">General</td>
		   	  				<td  style="border: 1px solid; padding:5px;"></td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td  rowspan="2" style="border: 1px solid; padding:5px;">History</td>
		   	  				<td  style="border: 1px solid; padding:5px;">Past History</td>
		   	  				<td  style="border: 1px solid; padding:5px;">{!! (isset($history_past->fldreportquali)) ? $history_past->fldreportquali : '' !!}</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td  style="border: 1px solid; padding:5px;">Family History</td>
		   	  				<td  style="border: 1px solid; padding:5px;">{!! (isset($history_family->fldreportquali)) ? $history_family->fldreportquali : '' !!}</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td  rowspan="2" style="border: 1px solid; padding:5px;">On Examination</td>
		   	  				<td  style="border: 1px solid; padding:5px;">Right</td>
		   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($exam_right->fldreportquali)) ? $exam_right->fldreportquali : '' }}</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td  style="border: 1px solid; padding:5px;">Left</td>
		   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($exam_left->fldreportquali)) ? $exam_left->fldreportquali : '' }}</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td colspan="2" style="border: 1px solid; padding:5px;">Current Medication</td>
		   	  				<td  style="border: 1px solid; padding:5px;">{!! (isset($current_medication->fldreportquali)) ? strip_tags($current_medication->fldreportquali) : '' !!}</td>
		   	  			</tr>
		   	  			<tr>
		   	  				<td colspan="2" style="border: 1px solid; padding:5px;">Digonosis</td>
		   	  				<td  style="border: 1px solid; padding:5px;"></td>
		   	  			</tr>
		   	  		</tbody>
		   	  	</table>
	   	  	</div>
	   </div>
	    <div class="img-table" style="width: 100%; margin-bottom: 5%;">
	   	   <table width="100%" border="0" cellspacing="0" cellpadding="0">
	   	   	   <tbody>
	   	   	   		<tr>
	   	   	   			<td rowspan="8">
	   	   	   				<div class="img-eye" style="height: 250px">
		   	   	   				<img src="{{ asset('assets/images/pdfeye.jpg')}}" style="width: 170;">
		   	   	   				@if(isset($eyeimage) && isset($eyeimage->left_eye))
		                        <img src="{{ $eyeimage->left_eye }}" style="width: 170;">
		                        @endif
		                    </div>
	   	   	   			</td>
	   	   	   			<td colspan="3" style="width: 35%; padding:20px;"></td>
	   	   	   			<td rowspan="8" style=" text-align: center;">
	   	   	   				<div class="img-eye" style="height: 250px">
		   	   	   				<img src="{{ asset('assets/images/pdfeye.jpg')}}" style="width: 170;">
		   	   	   				@if(isset($eyeimage) && isset($eyeimage->right_eye))
	                            <img src="{{ $eyeimage->right_eye }}" style="width: 170;">
	                            @endif
	                        </div>
	   	   	   			</td>
	   	   	   		</tr>
	   	   	   		<tr>
	   	   	   			<td style="border: 1px solid; text-align: center;">RE</td>
                        <td style="border: 1px solid; text-align: center;">Distance</td>
                        <td style="border: 1px solid; text-align: center;">LE</td>
                    </tr>
	   	   	   	   <tr>
                        <td style="border: 1px solid;">{{ (isset($unaided_distance_RE->fldreading)) ? $unaided_distance_RE->fldreading : '' }}</td>
                        <td style="border: 1px solid; text-align: center;">Unaided</td>
                        <td style="border: 1px solid;">{{ (isset($unaided_distance_LE->fldreading)) ? $unaided_distance_LE->fldreading : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid;">{{ (isset($aided_distance_RE->fldreading)) ? $aided_distance_RE->fldreading : '' }}</td>
                        <td style="border: 1px solid; text-align: center;">Aided</td>
                        <td style="border: 1px solid;">{{ (isset($aided_distance_LE->fldreading)) ? $aided_distance_LE->fldreading : '' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid;">{{ (isset($pinhole_distance_RE->fldreading)) ? $pinhole_distance_RE->fldreading : '' }}</td>
                        <td style="border: 1px solid; text-align: center;">Pinhole</td>
                        <td style="border: 1px solid;">{{ (isset($pinhole_distance_LE->fldreading)) ? $pinhole_distance_LE->fldreading : '' }}</td>
                    </tr>
	   	   	   </tbody>
	   	   </table>
	    </div>

		<div class="table-content" style="width: 100%;">
		  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  		<tbody>
		  			<tr>
	   	  				<td  colspan="2" style="padding:5px;border: 1px solid; "></td>
	   	  				<td  style="border: 1px solid; padding:5px;">Spherical</td>
	   	  				<td  style="border: 1px solid; padding:5px;">Cylindrical</td>
	   	  				<td  style="border: 1px solid; padding:5px;">Axis</td>
	   	  				<td  style="border: 1px solid; padding:5px;">Vision</td>
	   	  				<td rowspan="13" style="border: none; padding:5px; ">&nbsp;</td>
	   	  				<td colspan="2" style="border: 1px solid; padding:5px; text-align: center;">RE</td>
	    				<td style="border: 1px solid; padding:5px; width: 10%; text-align: center;">LOP</td>
	    				<td colspan="2" style="border: 1px solid; padding:5px; text-align: center;">LE</td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  rowspan="2" style="border: 1px solid; padding:5px; width: 20%">Auto Refraction</td>
	   	  				<td  style="border: 1px solid; padding:5px; width: 7%;">RE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($auto_reaction_spherical_RE->fldreading)) ? $auto_reaction_spherical_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($auto_reaction_cylindrical_RE->fldreading)) ? $auto_reaction_cylindrical_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($auto_reaction_axis_RE->fldreading)) ? $auto_reaction_axis_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
	   	  				<td style="border: 1px solid; padding:5px; width: 5%;"></td>
	    				<td style="border: 1px solid; padding:5px;">mmHg</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	    				<td style="border: 1px solid; padding:5px; width: 5%;"></td>
	    				<td style="border: 1px solid; padding:5px;">mmHg</td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  style="border: 1px solid; padding:5px;">LE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($auto_reaction_spherical_LE->fldreading)) ? $auto_reaction_spherical_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($auto_reaction_cylindrical_LE->fldreading)) ? $auto_reaction_cylindrical_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($auto_reaction_axis_LE->fldreading)) ? $auto_reaction_axis_LE->fldreading : ''  }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($AT_RE->fldreadingprefix)) ? $AT_RE->fldreadingprefix : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($AT_RE->fldreading)) ? $AT_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px; text-align: center;">AT</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($AT_LE->fldreadingprefix)) ? $AT_LE->fldreadingprefix : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($AT_LE->fldreading)) ? $AT_LE->fldreading : '' }}</td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  rowspan="2" style="border: 1px solid; padding:5px;">Add</td>
	   	  				<td  style="border: 1px solid; padding:5px;">RE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($add_spherical_RE->fldreading)) ? $add_spherical_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($add_vision_RE->fldreading)) ? $add_vision_RE->fldreading : '' }}</td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($NCT_RE->fldreadingprefix)) ? $NCT_RE->fldreadingprefix : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($NCT_RE->fldreading)) ? $NCT_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px; text-align: center;">NCT</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($NCT_LE->fldreadingprefix)) ? $NCT_LE->fldreadingprefix : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($NCT_LE->fldreading)) ? $NCT_LE->fldreading : '' }}</td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  style="border: 1px solid; padding:5px;">LE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($add_spherical_LE->fldreading)) ? $add_spherical_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($add_vision_LE->fldreading)) ? $add_vision_LE->fldreading : '' }}</td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($SA_RE->fldreadingprefix)) ? $SA_RE->fldreadingprefix : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($SA_RE->fldreading)) ? $SA_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px; text-align: center;">SA</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($SA_LE->fldreadingprefix)) ? $SA_LE->fldreadingprefix : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($SA_LE->fldreading)) ? $SA_LE->fldreading : '' }}</td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  rowspan="2" style="border: 1px solid; padding:5px;">Acceptance</td>
	   	  				<td  style="border: 1px solid; padding:5px;">RE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_spherical_RE->fldreading)) ? $acceptance_spherical_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_cylindrical_RE->fldreading)) ? $acceptance_cylindrical_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_axis_RE->fldreading)) ? $acceptance_axis_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_vision_RE->fldreading)) ? $acceptance_vision_RE->fldreading : '' }}</td>
	   	  				<td colspan="5" style="border: 1px solid; padding:9px; background-color: #eee;"></td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  style="border: 1px solid; padding:5px;">LE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_spherical_LE->fldreading)) ? $acceptance_spherical_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_cylindrical_LE->fldreading)) ? $acceptance_cylindrical_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_axis_LE->fldreading)) ? $acceptance_axis_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($acceptance_vision_LE->fldreading)) ? $acceptance_vision_LE->fldreading : '' }}</td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($schimers_test_type_I_RE->fldreading)) ? $schimers_test_type_I_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	    				<td style="border: 1px solid; padding:5px; text-align: center;">Schir-I</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($schimers_test_type_I_LE->fldreading)) ? $schimers_test_type_I_LE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  rowspan="2" style="border: 1px solid; padding:5px;">Previous Glass Prescription(PGP)</td>
	   	  				<td  style="border: 1px solid; padding:5px;">RE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($PGP_spherical_RE->fldreading)) ? $PGP_spherical_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($PGP_cylindrical_RE->fldreading)) ? $PGP_cylindrical_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($PGP_axis_RE->fldreading)) ? $PGP_axis_RE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
						<td style="border: 1px solid; padding:5px;">{{ (isset($schimers_test_type_II_RE->fldreading)) ? $schimers_test_type_II_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	    				<td style="border: 1px solid; padding:5px;text-align: center;">Schir-II</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($schimers_test_type_II_LE->fldreading)) ? $schimers_test_type_II_LE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  style="border: 1px solid; padding:5px;">LE</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($PGP_spherical_LE->fldreading)) ? $PGP_spherical_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($PGP_cylindrical_LE->fldreading)) ? $PGP_cylindrical_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;">{{ (isset($PGP_axis_LE->fldreading)) ? $PGP_axis_LE->fldreading : '' }}</td>
	   	  				<td  style="border: 1px solid; padding:5px;"></td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($schimers_test_type_III_RE->fldreading)) ? $schimers_test_type_III_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	    				<td style="border: 1px solid; padding:5px; text-align: center;">Schir-III</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($schimers_test_type_III_LE->fldreading)) ? $schimers_test_type_III_LE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	   	  				
	   	  			</tr>
	   	  			<tr>
	   	  				<td  rowspan="2" style="border: 1px solid; padding:5px;">Color Vision</td>
	   	  				<td  style="border: 1px solid; padding:5px;">RE</td>
	   	  				<td colspan="4" style="border: 1px solid; padding:5px;">{{ (isset($color_vision_axis_RE->fldreading)) ? $color_vision_axis_RE->fldreading : '' }}
	   	  				</td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($k_reading_k_I_RE->fldreading)) ? $k_reading_k_I_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	    				<td style="border: 1px solid; padding:5px;text-align: center;">K1</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($k_reading_k_I_LE->fldreading)) ? $k_reading_k_I_LE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td  style="border: 1px solid; padding:5px;">LE</td>
	   	  				<td colspan="4" style="border: 1px solid; padding:5px;">{{ (isset($color_vision_axis_LE->fldreading)) ? $color_vision_axis_LE->fldreading : '' }}
	   	  				</td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($k_reading_k_II_RE->fldreading)) ? $k_reading_k_II_RE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	    				<td style="border: 1px solid; padding:5px;text-align: center;">K2</td>
	    				<td style="border: 1px solid; padding:5px;">{{ (isset($k_reading_k_II_LE->fldreading)) ? $k_reading_k_II_LE->fldreading : '' }}</td>
	    				<td style="border: 1px solid; padding:5px;"></td>
	   	  			</tr>
	   	  			<tr>
	   	  				<td style="border: none; padding:5px;"></td>
	   	  				<td style="border: none; padding:5px;"></td>
	   	  				<td style="border: none; padding:5px;"></td>
	   	  				<td style="border: none; padding:5px;"></td>
	   	  				<td style="border: none; padding:5px;"></td>
	   	  				<td style="border: none; padding:5px;"></td>
	   	  				<td style="border: 1px solid; padding:5px;">{{ (isset($k_reading_k_III_RE->fldreading)) ? $k_reading_k_III_RE->fldreading : '' }}</td>
						<td style="border: 1px solid; padding:5px;"></td>
						<td style="border: 1px solid; padding:5px;text-align: center;">K3</td>
						<td style="border: 1px solid; padding:5px;">{{ (isset($k_reading_k_III_LE->fldreading)) ? $k_reading_k_III_LE->fldreading : '' }}</td>
						<td style="border: 1px solid; padding:5px;"></td>
				</tr>
		  		</tbody>
		  	</table>
		</div>

	  <div class="row">
			<div class="table-last" style="margin-top: 16px;">
				<table style="border: 1px solid;border-collapse: collapse; font-size: 18px; width: 99%;margin: 0 auto;">
					<tbody>
						<tr>
							<td  style="border: 1px solid; padding:5px; width: 25%;">Lab Test Advised</td>
							<td  style="border: 1px solid; padding:5px;"></td>
						</tr>
						<tr>
							<td  style="border: 1px solid; padding:5px;">Radio Examination Advised</td>
							<td  style="border: 1px solid; padding:5px;"></td>
						</tr>
						<tr>
							<td  style="border: 1px solid; padding:5px;">Treatment Advised</td>
							<td  style="border: 1px solid; padding:5px;"></td>
						</tr>
						<tr>
							<td  style="border: 1px solid; padding:5px;">Notes</td>
							<td  style="border: 1px solid; padding:5px;">{!! (isset($note->fldreportquali)) ? strip_tags($note->fldreportquali) : '' !!}</td>
						</tr>
						<tr>
							<td  style="border: 1px solid; padding:5px;">Advised</td>
							<td  style="border: 1px solid; padding:5px;">{!! (isset($advice->fldreportquali)) ? strip_tags($advice->fldreportquali) : '' !!}</td>
						</tr>
					</tbody>
				</table>
			</div>
	  </div>
	</div>
@endsection
