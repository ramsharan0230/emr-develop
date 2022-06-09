 '<tr>
      <td></td>
      <td>'. $details->flditem . '</td>
      <td>
         null
      </td>
      <td>'. $details->fldreportquali . '</td>
      <td><a href="javascript:;" permit_user="' . $details->flduserid . '" class="delete_complaints" route="" ><i class="far fa-trash-alt"></i></a></td>
      <td><a href="javascript:;" permit_user="'. $details->flduserid .'" data-toggle="modal" data-target="#edit_complaint_emergency" old_complaint_detail="'. $details->flddetai .'" class="clicked_edit_complaint" clicked_flag_val="'. $details->fldid .'">
      <i class="fas fa-edit"></i></a></td>
      <td>'. $details->fldtime .'</td>
      <td>'. strip_tags($details->flddetail) .'</td>
   </tr>';