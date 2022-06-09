<!-- Emergency Modal -->
<div class="modal fade" id="EmergencyModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Emergency Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table cursor" id="table_emergency"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr class="tr_emergency">
                                <td>Emergency</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered cursor" id="sub_emergency_table" style="max-height: 300px;">
                            <tbody id="sub_emergency_table_body">
                            <tr class="sub_tr_emergency">
                                <td>No data availlable</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="emergency_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End Emergency Modal -->

<!-- consultantModal Modal -->
<div class="modal fade" id="consultantModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">consultant Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table cursor" id="table_consultant"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="consolation_table">
                            <tr class="consolation_tr" data-info="General Treatment"><td>General Treatment</td></tr>
                            <tr class="consolation_tr" data-info="IMNCI"><td>IMNCI</td></tr>
                            <tr class="consolation_tr" data-info="Nutrition"> <td>Nutrition</td></tr>
                            <tr class="consolation_tr" data-info="Safe Motherhood"><td>Safe Motherhood</td></tr>
                            <tr class="consolation_tr" data-info="Family Planning"> <td>Family Planning</td></tr>
                            <tr class="consolation_tr" data-info="Tuberculosis"><td>Tuberculosis</td></tr>
                            <tr class="consolation_tr" data-info="Leprosy"><td>Leprosy</td></tr>
                            <tr class="consolation_tr" data-info="Infection"> <td>Infection</td></tr>
                            <tr class="consolation_tr" data-info="STD"> <td>STD</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered  cursor" id="sub_consultant_table" style="max-height: 300px;">
                            <tbody id="sub_consultant_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="consultant_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End consultantModal Modal -->

<!-- InPatient Modal -->
<div class="modal fade" id="InPatientModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">InPatient Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table cursor" id="table_inpatient"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr class="tr_inpatient" data-sub_category="General Medicine"><td>General Medicine</td></tr>
                            <tr class="tr_inpatient" data-sub_category="General Surgery"><td> General Surgery</td></tr>
                            <tr class="tr_inpatient" data-sub_category="Paediatrics"><td>Paediatrics</td></tr>
                            <tr class="tr_inpatient" data-sub_category="Obstetric"><td>Obstetric</td></tr>
                            <tr class="tr_inpatient" data-sub_category="Gynecology"><td>Gynecology</td></tr>
                            <tr class="tr_inpatient" data-sub_category="ENT"><td>ENT</td></tr>
                            <tr class="tr_inpatient" data-sub_category="Orthopedics"><td>Orthopedics</td></tr>
                            <tr class="tr_inpatient" data-sub_category="Psychiatric"><td>Psychiatric</td></tr>
                            <tr class="tr_inpatient" data-sub_category="Dental"><td>Dental</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered cursor" id="sub_inpatient_table" style="max-height: 300px;">
                            <tbody id="sub_inpatient_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="inpatient_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End InPatient Modal -->

<!-- anc Modal -->
<div class="modal fade" id="ancModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ANC Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table  cursor" id="table_anc"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;"><th class="text-center">Select Component</th></tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr class="tr_anc"><td>ANC</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered cursor" id="sub_anc_table" style="max-height: 300px;">
                            <tbody id="sub_anc_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="anc_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End anc Modal -->

<!-- Diagnostic Modal -->
<div class="modal fade" id="diagnosticModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Diagnostic Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table  cursor" id="table_diagnostic"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr     class="tr_diagnostic"  data-sub_category ="X-ray"><td>X-ray</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Ultrasonogram (USG)"><td>Ultrasonogram (USG)</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Echocardiogram (Echo)"><td>Echocardiogram (Echo)</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Electro Encephalo Gram (EEG)"><td>Electro Encephalo Gram (EEG)</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Electrocardiogram (ECG)"><td>Electrocardiogram (ECG)</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Trademill"><td>Trademill</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Computed Tomographic (CT) Scan"><td>Computed Tomographic (CT) Scan</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Magnetic Resonance Imaging (MRI)"><td>Magnetic Resonance Imaging (MRI)</td>
                            </tr>
                            <tr     class="tr_diagnostic"  data-sub_category ="Endoscopy"><td>Endoscopy</td>
                            </tr>
                            <tr class="tr_diagnostic"  data-sub_category ="Colonscopy">    <td>Colonscopy</td>
                            </tr>
                            <tr class="tr_diagnostic"  data-sub_category ="Neuclear Medicine"> <td>Neuclear Medicine</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered  cursor" id="sub_diagnostic_table" style="max-height: 300px;">
                            <tbody id="sub_diagnostic_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="diagnostic_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End Diagnostic Modal -->

<!-- Delivery Modal -->
<div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Delivery Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table  cursor" id="table_delivery"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr  class="tr_delivery" data-sub_category="Spontaneous"><td>Spontaneous</td></tr>
                            <tr  class="tr_delivery" data-sub_category="Vaccum"><td>Vaccum</td></tr>
                            <tr  class="tr_delivery" data-sub_category="Forceps"><td>Forceps</td></tr>
                            <tr class="tr_delivery" data-sub_category="Caeserian"> <td>Caeserian</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered  cursor" id="sub_delivery_table" style="max-height: 300px;">
                            <tbody id="sub_delivery_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="deliver_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End Delivery Modal -->

<!-- laboratory Modal -->
<div class="modal fade" id="laboratoryModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Laboratory Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table cursor" id="table_laboratory"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr class="tr_laboratory" data-sub_Category="HAEMATOLOGY">
                                <td>HAEMATOLOGY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="IMMUNOLOGY">
                                <td>IMMUNOLOGY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="BIOCHEMISTRY">
                                <td>BIOCHEMISTRY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="IMMUNO-HISTROCHEMESTRY">
                                <td>IMMUNO-HISTROCHEMESTRY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="BACTERIOLOGY">
                                <td>BACTERIOLOGY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="VIROLOGY">
                                <td>VIROLOGY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="CYTOLOGY">
                                <td>CYTOLOGY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="HISTOLOGY">
                                <td>HISTOLOGY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="PARASITOLOGY">
                                <td>PARASITOLOGY</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="CARDIAC ENZYMES">
                                <td>CARDIAC ENZYMES</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="HORMONS-ENDOCRINES">
                                <td>HORMONS-ENDOCRINES</td>
                            </tr>
                            <tr class="tr_laboratory" data-sub_Category="DRUG-ANALYSIS">
                                <td>DRUG-ANALYSIS</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered  cursor" id="sub_laboratory_table"
                               style="max-height: 300px;">
                            <tbody id="ssub_laboratory_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="laboratory_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End laboratory Modal -->

<!-- culture Modal -->
<div class="modal fade" id="cultureModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Culture Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table cursor" id="table_culture"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr class="tr_culture">
                                <td>Culture Sensitivity</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered cursor" id="sub_culture_table"
                               style="max-height: 300px;">
                            <tbody id="sub_culture_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="culture_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End Culture Modal -->

<!-- culture_specimens Modal -->
<div class="modal fade" id="cultureSpecimensModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Culture Specimens
                    Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table table-diagnosis cursor" id="table_culture_specimens"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">
                            <tr class="tr_culture_specimen" data-sub_category="Blood">
                                <td>Blood</td>
                            </tr>
                            <tr class="tr_culture_specimen" data-sub_category="Urine">
                                <td>Urine</td>
                            </tr>
                            <tr class="tr_culture_specimen" data-sub_category="Body Fluid">
                                <td>Body Fluid</td>
                            </tr>

                            <tr class="tr_culture_specimen" data-sub_category="Swab">
                                <td>Swab</td>
                            </tr>

                            <tr class="tr_culture_specimen" data-sub_category="Stool">
                                <td>Stool</td>
                            </tr>

                            <tr class="tr_culture_specimen" data-sub_category="Water">
                                <td>Water</td>
                            </tr>

                            <tr class="tr_culture_specimen" data-sub_category="Pus">
                                <td>Pus</td>
                            </tr>

                            <tr class="tr_culture_specimen" data-sub_category="Sputum">
                                <td>Sputum</td>
                            </tr>
                            <tr class="tr_culture_specimen" data-sub_category="ENT">
                                <td>ENT</td>
                            </tr>
                            <tr class="tr_culture_specimen" data-sub_category="CSF">
                                <td>CSF</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered cursor" id="sub_culture_specimens_table"
                               style="max-height: 300px;">
                            <tbody id="sub_culture_specimens_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="culture_sepcimen_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End culture_specimens Modal -->

<!-- free_service Modal -->
<div class="modal fade" id="FreeServiceModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Free Service Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px">
                        <table class="table-bordered table cursor" id="table_free_service"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Select Component</th>
                            </tr>
                            </thead>
                            <tbody  id="emergency_table">

                            <tr class="tr_free_service" data-sub_category="Ultra Poor/Poor">
                                <td>Ultra Poor/Poor</td>
                            </tr>

                            <tr class="tr_free_service" data-sub_category="Helpless/Destitute">
                                <td>Helpless/Destitute</td>
                            </tr>

                            <tr class="tr_free_service" data-sub_category="Disabled">
                                <td>Disabled</td>
                            </tr>

                            <tr class="tr_free_service" data-sub_category="Sr Citizen">
                                <td>Sr Citizen</td>
                            </tr>

                            <tr class="tr_free_service" data-sub_category="FCHV">
                                <td>FCHV</td>
                            </tr>

                            <tr class="tr_free_service" data-sub_category="Gender Based Voilence">
                                <td>Gender Based Voilence</td>
                            </tr>

                            <tr class="tr_free_service" data-sub_category="Others">
                                <td>Others</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px">
                        <table class="table table-bordered  cursor" id="sub_free_service_table"
                               style="max-height: 300px;">
                            <tbody id="sub_free_service_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="free_service_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End free_service Modal -->

<!-- Laboratory Service/Test Mapping  Modal -->
<div class="modal fade" id="LabServiceModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Test Mapping</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label >Please Select Options</label>
                        <select name="lab_options" id="lab_options" class="form-control">
                            <option value="">--Select--</option>
                            <option value="HAEMATOLOGY">HAEMATOLOGY </option>
                            <option value="IMMUNOLOGY">IMMUNOLOGY </option>
                            <option value="BIOCHEMISTRY">BIOCHEMISTRY </option>
                            <option value="IMMUNO-HISTROCHEMESTRY">IMMUNO-HISTROCHEMESTRY </option>
                            <option value="BACTERIOLOGY">BACTERIOLOGY </option>
                            <option value="VIROLOGY">VIROLOGY </option>
                            <option value="CYTOLOGY">CYTOLOGY </option>
                            <option value="PARASITOLOGY">PARASITOLOGY </option>
                            <option value="HORMONES-ENDOCRINES">HORMONES-ENDOCRINES</option>
                            <option value="DRUG-ANALYSIS">DRUG-ANALYSIS</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table-bordered table cursor" id="table_laboratory_service"
                               style="margin-bottom: 0px;">
                            <thead>
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">Local Options</th>
                            </tr>
                            </thead>
                            <tbody  id="labservice_table">
                            <tr>
                                <td>No data availlable</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6" style="overflow: auto;height: 300px;">
                        <table class="table table-bordered  cursor" id="sub_laboratory_service_table"
                               style="max-height: 300px;">
                            <tr style="background: #28a745;color: #ffffff;">
                                <th class="text-center">DB Options</th>
                            </tr>
                            <tbody id="sub_laboratory_service_table_body">
                            <tr>
                                <td>No data availlable</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary" id="laboratory_service_save">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End Laboratory service Modal-->


<!-- Modal for ALredy existed data -->
<!-- Modal -->
<div class="modal fade" id="dataExistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Data already exists</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col" style="text-align:center">S.N</th>
                        <th scope="col" style="text-align:center">Category</th>
                        <th scope="col" style="text-align:center">Sub Category</th>
                        <th scope="col"style="text-align:center">Service</th>
                    </tr>
                    </thead>
                    <tbody id="dataexistbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
{{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
            </div>
        </div>
    </div>
</div>

<!-- ENd Modal for ALready existed data -->

