<div id="treatment" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
        <div class="iq-card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Ultrasound Therapy (UST) :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                            <div class="col-sm-3">
                                <label for="">Mode</label>
                                <select name="ust_mode" id="ust_mode" class="form-control">
                                    <option value="Pulsed">Pulsed</option>
                                    <option value="Continuous">Continuous</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <label for="">Frequency</label>
                                <select name="ust_frequency" id="ust_frequency" class="form-control">
                                    <option value="1MHz">1MHz</option>
                                    <option value="3MHz">3MHz</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <label for="">Intensity( W/cm2)</label>
                                <input type="text" class="form-control" id="ust_intensity" value="" autocomplete="off">
                            </div>

                            <div class="col-sm-2">
                                <label for="">Time (minutes)</label>
                                <input type="text" class="form-control" id="ust_time" value="" autocomplete="off">
                            </div>

                            <div class="col-sm-2">
                                <label for="">Site</label>
                                <input type="text" class="form-control" id="ust_site" value="" autocomplete="off">
                            </div>

                            <div class="col-sm-1">
                                <label for="">Days</label>
                                <input type="text" class="form-control" id="ust_days" value="" autocomplete="off">
                            </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Trans-cutaneous Electrical Nerve Stimulation (TENS) :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">
                        <div class="col-sm-2">
                            <label for="">Mode</label>
                            <select name="tens_mode" id="tens_mode" class="form-control">
                                <option value="Sweep">Sweep</option>
                                <option value="Burst">Burst</option>
                                <option value="Cont.">Cont.</option>
                                <option value="Nodulated Burst">Nodulated Burst</option>
                                <option value="Conventional/ High Frequency">Conventional/ High Frequency</option>
                                <option value="Low Frequency">Low Frequency</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Frequency</label>
                            <select name="tens_frequency" id="tens_frequency" class="form-control">
                                <option value="2 Hz">2 Hz</option>
                                <option value="5 Hz">5 Hz</option>
                                <option value="10 Hz">10 Hz</option>
                                <option value="50 Hz">50 Hz</option>
                                <option value="100 Hz">100 Hz</option>
                                <option value="200 Hz">200 Hz</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Intensity( mA )</label>
                            <input type="text" class="form-control" id="tens_intensity" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="tens_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="tens_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="tens_days" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Channel</label>
                            <select name="ust_channel" id="ust_channel" class="form-controlust_channel">
                                <option value="2">2</option>
                                <option value="4">4</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Inferrential Therapy(IFT) :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">


                        <div class="col-sm-1">
                            <label for="">Channel</label>
                            <select name="ift_mode" id="ift_mode" class="form-control">
                                <option value="2">2</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="col-sm-1">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="ift_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Program Selection</label>
                            <input type="text" class="form-control" id="ift_program_selection" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Intensity( mA )</label>
                            <input type="text" class="form-control" id="ift_intensity" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Treatment Mode</label>
                            <select name="ift_mode" id="ift_treatment_mode" class="form-control">
                                <option value="2 pole">2 pole</option>
                                <option value="4 pole">4 pole</option>
                                <option value="4 pole vector">4 pole vector</option>
                            </select>
                        </div>

                        <div class="col-sm-1">
                            <label for="">Frequency</label>
                            <select name="ift_frequency" id="ift_frequency" class="form-control">
                                <option value="2 KHz">2 KHz</option>
                                <option value="4 Hz">4 Hz</option>
                            </select>

                        </div>

                        <div class="col-sm-2">
                            <label for="">Time (mins)</label>
                            <input type="text" class="form-control" id="ift_time" value="" autocomplete="off">

                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="ift_days" value="" autocomplete="off">

                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Traction :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">

                        <div class="col-sm-2">
                            <label for="">Mode</label>
                            <select name="traction_mode" id="traction_mode" class="form-control">
                                <option value="Continuous">Continuous</option>
                                <option value="Intermittent">Intermittent</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Hold Time (secs)</label>
                            <select name="traction_hold_time" id="traction_hold_time" class="form-control">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                                <option value="60">60</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Rest Time (secs)</label>
                            <select name="traction_rest_time" id="traction_rest_time" class="form-control">
                                <option value="1">1</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Weight(kgs)</label>
                            <select name="traction_weight" id="traction_weight" class="form-control">6
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                                <option value="12">12</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="30">30</option>
                                <option value="35">35</option>
                                <option value="40">40</option>
                                <option value="45">45</option>
                            </select>
                        </div>

                        <div class="col-sm-1">
                            <label for="" class="text-center">Types</label>
                            <select name="traction_types" id="traction_types" class="form-control">
                                <option value="Cervical">Cervical</option>
                                <option value="Lumber">Lumber</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Time (mins)</label>
                            <input type="text" class="form-control" id="traction_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="traction_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Electrical Muscle Stimulator (EMS) :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">

                        <div class="col-sm-2">
                            <label for="">Mode</label>
                            <select name="ems_mode" id="ems_mode" class="form-control">
                                <option value="Galvanic">Galvanic</option>
                                <option value="IG">IG</option>
                                <option value="Faradic">Faradic</option>
                                <option value="◦Surged faradic">Surged faradic</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Intensity( mA )</label>
                            <input type="text" class="form-control" id="ems_intensity" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Pulse Duration (μs)</label>
                            <select name="ems_pulse_duration" id="ems_pulse_duration" class="form-control">
                                <option value=".01">.01</option>
                                <option value=".03">.03</option>
                                <option value=".1">.1</option>
                                <option value=".3">.3</option>
                                <option value=".10">.10</option>
                                <option value=".30">.30</option>
                                <option value=".100">.100</option>
                                <option value=".300">.300</option>
                                <option value=".1000">.1000</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Surge seconds (sec)</label>
                            <select name="ems_surge_seconds" id="ems_surge_seconds" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="ems_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="ems_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Infrared Radiation (IRR) :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-items-center">

                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="irr_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="irr_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="irr_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Short wave diathermy (SWD) :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">

                        <div class="col-sm-2">
                            <label for="">Application Method</label>
                            <select name="swd_application_mode" id="swd_application_mode" class="form-control">
                                <option value="Contraplanar">Contraplanar</option>
                                <option value="Co-planar">Co-planar</option>
                                <option value="Cross-fire">Cross-fire</option>
                                <option value="Monopolar">Monopolar</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Frequency</label>
                            <select name="swd_frequency" id="swd_frequency" class="form-control">
                                <option value="27.12 MHz">27.12 MHz</option>
                                <option value="13.56 MHz">13.56 MHz</option>
                                <option value="40.68 MHz">40.68 MHz</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Intensity(Watt)</label>
                            <input type="text" class="form-control" id="swd_intensity" value="" autocomplete="off">
                        </div>


                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="swd_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="swd_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Microwave Diathermy :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">

                        <div class="col-sm-2">
                            <label for="">Frequency</label>
                            <select name="md_frequency" id="md_frequency" class="form-control">
                                <option value="2450 MHz">2450 MHz</option>
                                <option value="915 MHz">915 MHz</option>
                                <option value="433.92 MHz">433.92 MHz</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Intensity(Watt)</label>
                            <input type="text" class="form-control" id="md_intensity" value="" autocomplete="off">
                        </div>


                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="md_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="md_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="md_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Wax Bath :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">

                        <div class="col-sm-2">
                            <label for="">Methods</label>
                            <select name="wax_bath_methods" id="wax_bath_methods" class="form-control">
                                <option value="Brush">Brush</option>
                                <option value="Dip">Dip</option>
                                <option value="Immersion">Immersion</option>
                                <option value="Direct pouring">Direct pouring</option>
                                <option value="Bandaging">Bandaging</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="wax_bath_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="wax_bath_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="wax_bath_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Moist Heat Pack :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">

                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="moist_head_pack_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="moist_head_pack_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="moist_head_pack_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Cryotherapy :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">

                        <div class="col-sm-2">
                            <label for="">Temperature (C/F)</label>
                            <input type="text" class="form-control" id="cryotherapy_temperature" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="cryotherapy_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="cryotherapy_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="cryotherapy_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Laser :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">

                        <div class="col-sm-2">
                            <label for="">Program Selection</label>
                            <input type="text" class="form-control" id="laser_program_selection" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Time (minutes)</label>
                            <input type="text" class="form-control" id="laser_time" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="laser_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-1">
                            <label for="">Days</label>
                            <input type="text" class="form-control" id="laser_days" value="" autocomplete="off">
                        </div>

                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="iq-card-header d-flex justify-content-between p-0">
                        <div class="iq-header-title">
                            <h4 class="card-title">Extra Corporeal Shock Wave Therapy :</h4>
                        </div>
                    </div>
                    <div class="form-group form-row align-it-center">

                        <div class="col-sm-2">
                            <label for="">Site</label>
                            <input type="text" class="form-control" id="ecswt_site" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-3">
                            <label for="">Energy Flux Density (J/cm2)</label>
                            <input type="text" class="form-control" id="ecswt_energy_flux_density" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Frequency</label>
                            <input type="text" class="form-control" id="ecswt_frequency" value="" autocomplete="off">
                        </div>

                        <div class="col-sm-2">
                            <label for="">Session</label>
                            <input type="text" class="form-control" id="ecswt_session" value="" autocomplete="off">
                        </div>


                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group form-row align-items-center mt-3">
                        <div class="col-sm-3">
                            <button type="add" id="js-treatment-add-btn"  class="btn btn-primary {{ $disableClass }}" type="button" url="{{ route('physiotherapy.treatment.save') }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="Save">
                                <i class="fa fa-check"></i>&nbsp;Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
