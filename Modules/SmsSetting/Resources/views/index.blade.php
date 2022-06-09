@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <h3 class="card-title">
                                SMS Settings
                            </h3>
                        </div>
                        <div>
                            <a type="button" class="btn btn-outline-primary" href="{{ route('smssetting.reset') }}"
                                id="reset_sms"><i class="fa fa-sync"></i>&nbsp;Reset</a>
                        </div>

                    </div>
                    <form>
                        <div class="iq-card-body">
                            <div class="form-row">
                                <div class="col-sm-3">
                                    <label for="sms_type_search">SMS Type</label>
                                    <div class="">
                                        <select name="sms_type_search" id="sms_type_search" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($sms_type as $st)
                                                <option value="{{ $st }}"
                                                    {{ $st == $sms_type_search ? 'selected' : '' }}>{{ $st }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label for="sms_name_search">SMS Name</label>
                                    <div class="">
                                        <select name="sms_name_search" id="sms_name_search" class="form-control">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    {{-- <input type="text" id="sms_name_search" name="sms_name_search" value="" class="form-control" placeholder=""/> --}}
                                </div>
                                <div class="col-sm-3">
                                    <div class="d-flex flex-column justify-content-start">
                                        <label for="status_search">Status </label>
                                        <div class="d-flex flex-row">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="status_search" id="status_active_search"
                                                    value="Active" class="custom-control-input" checked>
                                                <label class="custom-control-label" for="status_active"> Active </label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="status_search" id="status_inactive_search"
                                                    class="custom-control-input"
                                                    {{ $status_search == 'Inactive' ? 'checked' : '' }} value="Inactive">
                                                <label class="custom-control-label" for="status_inactive"> Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-4">
                                    <button class="btn btn-primary btn-action float-right mr-2" type="submit" id="refresh"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="">
            <div class="iq-card iq-card-block">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h3 class="card-title">
                            SMS List
                        </h3>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" id="add_sms"><i class="fa fa-plus"></i>&nbsp;Add</button>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="form-group">
                        <div class="table-responsive" id="sms_result">
                            <table id="myTable1" data-show-columns="true" data-search="true" data-show-toggle="true"
                                data-pagination="true" data-resizable="true">
                                <thead>
                                    <tr>
                                        <th class="text-center">S.N</th>
                                        <th class="text-center">SMS Type</th>
                                        <th class="text-center">SMS Name</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">SMS Condition Details</th>
                                        <th class="text-center">SMS Message</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="smslist">
                                @if($html !="")
                                {!! $html !!}
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--SMS Modal-->

    <div class="modal fade" id="add_sms_modal">
        <form method="POST" id="add-sms-form">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add SMS</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <label for="sms_type">SMS Type</label>
                                    <select name="sms_type" id="sms_type" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Follow Up">Follow Up</option>
                                        <option value="Deposit">Deposit</option>
                                        <option value="Events">Events</option>
                                        <option value="Lab">Lab</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sms_name">SMS Name</label>
                                    <input type="text" id="sms_name" name="sms_name" value="" class="form-control"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="d-flex flex-column">
                                        <label for="status">Status</label>
                                        <div class="d-flex flex-row">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="status" id="status_active" value="Active" checked
                                                    class="custom-control-input">
                                                <label class="custom-control-label" for="status_active"> Active </label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" name="status" id="status_inactive"
                                                    class="custom-control-input" value="Inactive">
                                                <label class="custom-control-label" for="status_inactive"> Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="follow_up_day" style="display: none;">
                                    <div class="form-group">
                                        <label for="free_follow_up_day">Free Followup Remaining Days </label>
                                        <input type="number" id="free_follow_up_day" name="free_follow_up_day" value=""
                                            max="{{ Options::get('followup_days') }}" class="form-control followup_days"
                                            placeholder="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="deposit" style="display: none;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="deposit_condition">Condition </label>
                                                <select name="deposit_condition" id="deposit_condition"
                                                    class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Deposit">Deposit</option>
                                                    <option value="Expenses">Expenses</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="deposit_mode">Mode </label>
                                                <select name="deposit_mode" id="deposit_mode" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value=">">> Greater Than</option>
                                                    <option value="<">
                                                        < Less Than</option>
                                                    <option value="=">= Equals To</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="deposit_amount_text" style="display: none;">
                                            <div class="form-group">
                                                <label for="deposit_amount"></label>
                                                <input type="text" id="deposit_amount" name="deposit_amount" value=""
                                                    class="form-control" placeholder="Deposit Amount" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="deposit_percentage_text" style="display: none;">
                                            <div class="form-group">
                                                <label for="deposit_percentage">Deposit Percentage(%) </label>
                                                <input type="text" id="deposit_percentage" name="deposit_percentage"
                                                    value="" class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="events" style="display: none;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="events_condition">Patient Visits Frequency </label>
                                                <select name="events_condition" id="events_condition"
                                                    class="form-control">
                                                    <option value="">Select</option>
                                                    <option value=">">> Greater Than</option>
                                                    <option value="<">
                                                        < Less Than</option>
                                                    <option value="=">= Equals To</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="visit_per_year">Number of Visits Per Year </label>
                                                <input type="text" id="visit_per_year" name="visit_per_year" value=""
                                                    class="form-control" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="lab" style="display: none;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="test_name">Test Name </label>
                                                <select name="test_name[]" id="test_name" class="form-control select2"
                                                    multiple>
                                                    @foreach ($test as $t)
                                                        <option data-id="{{ $t->fldtype }}" value="{{ $t->fldtestid }}">
                                                            {{ $t->fldtestid }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="test_status">Test Status </label>
                                                <select name="test_status" id="test_status" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Abnormal">Abnormal</option>
                                                    <option value="Normal">Normal</option>
                                                </select>
                                                {{-- <input type="text" id="test_status" name="test_status" value="" class="form-control" placeholder=""/> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="lab_test_details" style="display: none;">
                                <div class="form-group">
                                    <d-flex class="flex-column">
                                        <label for="test_details">Test Details</label>
                                        <textarea id="test_details" name="test_details" class="form-control" placeholder="Test Details"></textarea>
                                    </d-flex>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <d-flex class="flex-column">
                                        <label for="status">SMS Text Details</label>
                                        <textarea id="sms-details-textarea" name="sms_details" class="form-control"
                                            placeholder="SMS Text Details"></textarea>
                                    </d-flex>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose btn-action"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-action"><i
                                class="fa fa-save"></i>&nbsp;Save</button>
                        {{-- <a href="javascript:;" id="save-sms-form" class="btn btn-primary onclose btn-action" data-dismiss="modal"><i class="fa fa-save"></i>&nbsp;Save</a> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- END SMS Modal-->

    <!--View SMS Modal-->
    @foreach ($resultData as $d)
        <div class="modal fade" id="view-modal-{{ $d->id }}" tabindex="-1" role="dialog"
            aria-labelledby="ItemModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">View SMS</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-row">
                                    <label for="sms_type_view_{{ $d->id }}">SMS Type </label>
                                    <select name="sms_type_view" id="sms_type_view_{{ $d->id }}"
                                        class="form-control" disabled>
                                        <option value="">Select</option>
                                        @if ($d->sms_type == 'Follow Up')
                                            <option value="Follow Up" selected>Follow Up</option>
                                            <option value="Deposit">Deposit</option>
                                            <option value="Events">Events</option>
                                            <option value="Lab">Lab</option>
                                        @elseif($d->sms_type == 'Deposit')
                                            <option value="Follow Up">Follow Up</option>
                                            <option value="Deposit" selected>Deposit</option>
                                            <option value="Events">Events</option>
                                            <option value="Lab">Lab</option>
                                        @elseif($d->sms_type == 'Events')
                                            <option value="Follow Up">Follow Up</option>
                                            <option value="Deposit">Deposit</option>
                                            <option value="Events" selected>Events</option>
                                            <option value="Lab">Lab</option>
                                        @elseif($d->sms_type == 'Lab')
                                            <option value="Follow Up">Follow Up</option>
                                            <option value="Deposit">Deposit</option>
                                            <option value="Events">Events</option>
                                            <option value="Lab" selected>Lab</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sms_name_view_{{ $d->id }}">SMS Name </label>
                                    <input type="text" id="sms_name_view_{{ $d->id }}" name="sms_name_view"
                                        value="{{ $d->sms_name }}" readonly class="form-control" placeholder="" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="d-flex flex-column">
                                        <label for="status_view_{{ $d->id }}">Status </label>
                                        <div class="d-flex flex-row">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="checkbox" name="status_view"
                                                    id="status_active_view_{{ $d->id }}" value="Active"
                                                    class="custom-control-input"
                                                    {{ $d->status == 'Active' ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="status_active_view_{{ $d->id }}"> Active </label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="checkbox" name="status_view"
                                                    id="status_inactive_view_{{ $d->id }}"
                                                    class="custom-control-input" value="Inactive"
                                                    {{ $d->status == 'Inactive' ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="status_inactive_view_{{ $d->id }}"> Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="follow_up_day_view_{{ $d->id }}"
                                style="{{ $d->sms_type != 'Follow Up' ? 'display: none;' : '' }}">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="free_follow_up_day_view_view_{{ $d->id }}">Free Followup
                                            Remaining Days </label>
                                        <input type="text" id="free_follow_up_day_view_{{ $d->id }}"
                                            name="free_follow_up_day_view" value="{{ $d->free_follow_up_day }}"
                                            class="form-control" readonly placeholder="" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="deposit_view_{{ $d->id }}"
                                @if ($d->sms_type == 'Deposit') @else style="display: none;" @endif>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="deposit_condition_view_{{ $d->id }}">Condition </label>
                                                <select name="deposit_condition_view"
                                                    id="deposit_condition_view_{{ $d->id }}" class="form-control"
                                                    disabled>
                                                    <option value="">Select</option>
                                                    @if ($d->deposit_condition == 'Deposit')
                                                        <option value="Deposit" selected>Deposit</option>
                                                        <option value="Expenses">Expenses</option>
                                                    @elseif($d->deposit_condition == 'Expenses')
                                                        <option value="Deposit">Deposit</option>
                                                        <option value="Expenses" selected>Expenses</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="deposit_mode_view_{{ $d->id }}">Mode </label>
                                                <select name="deposit_mode_view" id="deposit_mode_view_{{ $d->id }}"
                                                    class="form-control" disabled>
                                                    <option value="">Select</option>
                                                    @if ($d->deposit_mode == '>')
                                                        <option value=">" selected>> Greater Than</option>
                                                        <option value="<">
                                                            < Less Than</option>
                                                        <option value="=">= Equals To</option>
                                                    @elseif($d->deposit_mode == '<')
                                                        <option value=">">> Greater Than</option>
                                                        <option value="<" selected>
                                                            < Less Than</option>
                                                        <option value="=">= Equals To</option>
                                                    @elseif($d->deposit_mode == '=')
                                                        <option value=">">> Greater Than</option>
                                                        <option value="<">
                                                            < Less Than</option>
                                                        <option value="=" selected>= Equals To</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="deposit_amount_text_view_{{ $d->id }}"
                                            @if (isset($d->deposit_amount)) @else style="display: none;" @endif>
                                            <div class="form-group">
                                                <label for="deposit_amount_view_{{ $d->id }}">Deposit Amount
                                                </label>
                                                <input type="text" id="deposit_amount_view_{{ $d->id }}"
                                                    name="deposit_amount_view" value="" class="form-control" readonly
                                                    placeholder="Deposit Amount" />
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="deposit_percentage_text_view_{{ $d->id }}"
                                            @if (isset($d->deposit_percentage)) @else style="display: none;" @endif>
                                            <div class="form-group">
                                                <label for="deposit_percentage_view_{{ $d->id }}">Deposit
                                                    Percentage(%) </label>
                                                <input type="text" id="deposit_percentage_view_{{ $d->id }}"
                                                    name="deposit_percentage_view" value="{{ $d->deposit_percentage }}"
                                                    class="form-control" readonly placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="events_view_{{ $d->id }}"
                                @if ($d->sms_type == 'Events') @else style="display: none;" @endif>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="events_condition_view_{{ $d->id }}">Patient Visits
                                                    Frequency </label>
                                                <select name="events_condition_view"
                                                    id="events_condition_view_{{ $d->id }}" class="form-control"
                                                    disabled>
                                                    <option value="">Select</option>
                                                    @if ($d->events_condition == '>')
                                                        <option value=">" selected>> Greater Than</option>
                                                        <option value="<">
                                                            < Less Than</option>
                                                        <option value="=">= Equals To</option>
                                                    @elseif($d->events_condition == '<')
                                                        <option value=">">> Greater Than</option>
                                                        <option value="<" selected>
                                                            < Less Than</option>
                                                        <option value="=">= Equals To</option>
                                                    @elseif($d->events_condition == '=')
                                                        <option value=">">> Greater Than</option>
                                                        <option value="<">
                                                            < Less Than</option>
                                                        <option value="=" selected>= Equals To</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="visit_per_year_view_{{ $d->id }}">Number of Visits Per
                                                    Year </label>
                                                <input type="text" id="visit_per_year_view_{{ $d->id }}"
                                                    name="visit_per_year_view" value="{{ $d->visit_per_year }}"
                                                    class="form-control" readonly placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="lab_view_{{ $d->id }}"
                                @if ($d->sms_type == 'Lab') @else style="display: none;" @endif>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="test_name_view_{{ $d->id }}">Test Name </label>
                                                @php
                                                    $test_names = $d->test_name ? json_decode($d->test_name, 'true') : [];
                                                @endphp
                                                <select name="test_name_view[]" data-id="{{ $d->id }}" id="test_name_view_{{ $d->id }}"
                                                    class="form-control select2 test_name_view" multiple disabled>
                                                    @foreach ($test as $t)
                                                        <option @if (in_array($t->fldtestid, $test_names)) selected @endif
                                                            value="{{ $t->fldtestid }}">{{ $t->fldtestid }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="test_status_view_{{ $d->id }}">Test Status </label>
                                                <select name="test_status_view" id="test_status_view_{{ $d->id }}"
                                                    class="form-control" disabled>
                                                    <option value="">Select</option>
                                                    @if ($d->test_status == 'Abnormal')
                                                        <option value="Abnormal" selected>Abnormal</option>
                                                        <option value="Normal">Normal</option>
                                                    @elseif($d->test_status == 'Normal')
                                                        <option value="Abnormal">Abnormal</option>
                                                        <option value="Normal" selected>Normal</option>
                                                    @endif
                                                </select>
                                                {{-- <input type="text" id="test_status" name="test_status" value="" class="form-control" placeholder=""/> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="lab_test_details_view_{{ $d->id }}" @if (isset($d->test_details)) @else style="display: none;" @endif>
                                <div class="form-group">
                                    <d-flex class="flex-column">
                                        <label for="test_details_view_{{ $d->id }}">Test Details</label>
                                        <textarea id="test_details_view_{{ $d->id }}" name="test_details_view" class="form-control" placeholder="Test Details" readonly>
                                        @if (isset($d->test_details))
                                        {{ $d->test_details }}
                                        @endif
                                        </textarea>
                                    </d-flex>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="SMS Text Details">SMS Text Detail</label>
                                    <textarea id="sms-details-textarea-view-{{ $d->id }}" name="sms_details_view" class="form-control"
                                        placeholder="SMS Text Details" readonly>
                                    @if (isset($d->sms_details))
                                    {{ $d->sms_details }}
                                    @endif
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                        {{-- <a href="javascript:;" id="save-sms-form" url="{{ route('save.department.bed') }}" class="btn btn-primary onclose" data-dismiss="modal">Save</a> --}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- END View SMS Modal-->

    <!--Edit SMS Modal-->
    @foreach ($resultData as $d)
        <div class="modal fade" id="edit-modal-{{ $d->id }}" tabindex="-1" role="dialog"
            aria-labelledby="ItemModal" aria-hidden="true">
            <form method="POST" id="edit-sms-form-{{ $d->id }}" class="edit-sms-form"
                data-id="{{ $d->id }}">
                <input type="hidden" name="id" value="{{ $d->id }}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Edit SMS</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-row">
                                        <label for="sms_type_edit_{{ $d->id }}">SMS Type </label>
                                        <select name="sms_type_edit" id="sms_type_edit_{{ $d->id }}"
                                            data-id="{{ $d->id }}" class="form-control sms_type_edit">
                                            <option value="">Select</option>
                                            @if ($d->sms_type == 'Follow Up')
                                                <option value="Follow Up" selected>Follow Up</option>
                                                <option value="Deposit">Deposit</option>
                                                <option value="Events">Events</option>
                                                <option value="Lab">Lab</option>
                                            @elseif($d->sms_type == 'Deposit')
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deposit" selected>Deposit</option>
                                                <option value="Events">Events</option>
                                                <option value="Lab">Lab</option>
                                            @elseif($d->sms_type == 'Events')
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deposit">Deposit</option>
                                                <option value="Events" selected>Events</option>
                                                <option value="Lab">Lab</option>
                                            @elseif($d->sms_type == 'Lab')
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deposit">Deposit</option>
                                                <option value="Events">Events</option>
                                                <option value="Lab" selected>Lab</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sms_name_edit_{{ $d->id }}">SMS Name </label>
                                        <input type="text" id="sms_name_edit_{{ $d->id }}" name="sms_name_edit"
                                            value="{{ $d->sms_name }}" class="form-control" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex flex-column">
                                            <label for="status_edit_{{ $d->id }}">Status </label>
                                            <div class="d-flex flex-row">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="status_edit"
                                                        id="status_active_edit_{{ $d->id }}" value="Active"
                                                        class="custom-control-input"
                                                        @if ($d->status == 'Active') checked @endif>
                                                    <label class="custom-control-label"
                                                        for="status_active_edit_{{ $d->id }}"> Active </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="status_edit"
                                                        id="status_inactive_edit_{{ $d->id }}"
                                                        class="custom-control-input" value="Inactive"
                                                        @if ($d->status == 'Inactive') checked @endif>
                                                    <label class="custom-control-label"
                                                        for="status_inactive_edit_{{ $d->id }}"> Inactive</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" id="follow_up_day_edit_{{ $d->id }}"
                                        @if ($d->sms_type == 'Follow Up') @else style="display: none;" @endif>
                                        <div class="form-group">
                                            <label for="free_follow_up_day_edit_{{ $d->id }}">Free Followup
                                                Remaining Days </label>
                                            <input type="number" id="free_follow_up_day_edit_{{ $d->id }}"
                                                name="free_follow_up_day_edit" value="{{ $d->free_follow_up_day }}"
                                                max="{{ Options::get('followup_days') }}" class="form-control"
                                                placeholder="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="deposit_edit_{{ $d->id }}"
                                    @if ($d->sms_type == 'Deposit') @else style="display: none;" @endif>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="deposit_condition_edit_{{ $d->id }}">Condition
                                                    </label>
                                                    <select name="deposit_condition_edit" data-id="{{ $d->id }}"
                                                        id="deposit_condition_edit_{{ $d->id }}"
                                                        class="form-control deposit_condition_edit">
                                                        <option value="">Select</option>
                                                        @if ($d->deposit_condition == 'Deposit')
                                                            <option value="Deposit" selected>Deposit</option>
                                                            <option value="Expenses">Expenses</option>
                                                        @elseif($d->deposit_condition == 'Expenses')
                                                            <option value="Deposit">Deposit</option>
                                                            <option value="Expenses" selected>Expenses</option>
                                                        @else
                                                            <option value="Deposit">Deposit</option>
                                                            <option value="Expenses">Expenses</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="deposit_mode_edit_{{ $d->id }}">Mode </label>
                                                    <select name="deposit_mode_edit"
                                                        id="deposit_mode_edit_{{ $d->id }}" class="form-control">
                                                        <option value="">Select</option>
                                                        @if ($d->deposit_mode == '>')
                                                            <option value=">" selected>> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->deposit_mode == '<')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<" selected>
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->deposit_mode == '=')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=" selected>= Equals To</option>
                                                        @else
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="deposit_amount_text_edit_{{ $d->id }}"
                                                @if (isset($d->deposit_amount)) @else style="display: none;" @endif>
                                                <div class="form-group">
                                                    <label for="deposit_amount_edit_{{ $d->id }}"></label>
                                                    <input type="text" id="deposit_amount_edit_{{ $d->id }}"
                                                        name="deposit_amount_edit" value="{{ $d->deposit_amount }}" class="form-control"
                                                        placeholder="Deposit Amount" readonly />
                                                </div>
                                            </div>
                                            <div class="col-md-6"
                                                id="deposit_percentage_text_edit_{{ $d->id }}"
                                                @if (isset($d->deposit_percentage)) @else style="display: none;" @endif>
                                                <div class="form-group">
                                                    <label for="deposit_percentage_edit_{{ $d->id }}">Deposit
                                                        Percentage(%) </label>
                                                    <input type="text" id="deposit_percentage_edit_{{ $d->id }}"
                                                        name="deposit_percentage_edit" value="{{ $d->deposit_percentage }}"
                                                        class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="events_edit_{{ $d->id }}"
                                    @if ($d->sms_type == 'Events') @else style="display: none;" @endif>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="events_condition_edit_{{ $d->id }}">Patient Visits
                                                        Frequency </label>
                                                    <select name="events_condition_edit"
                                                        id="events_condition_edit_{{ $d->id }}"
                                                        class="form-control">
                                                        <option value="">Select</option>
                                                        @if ($d->events_condition == '>')
                                                            <option value=">" selected>> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->events_condition == '<')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<" selected>
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->events_condition == '=')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=" selected>= Equals To</option>
                                                        @else
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="visit_per_year_edit_{{ $d->id }}">Number of Visits
                                                        Per Year </label>
                                                    <input type="text" id="visit_per_year_edit_{{ $d->id }}"
                                                        name="visit_per_year_edit" value="{{ $d->visit_per_year }}"
                                                        class="form-control" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="lab_edit_{{ $d->id }}"
                                    @if ($d->sms_type == 'Lab') @else style="display: none;" @endif>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="test_name_edit_{{ $d->id }}">Test Name </label>
                                                    @php
                                                        $test_names = $d->test_name ? json_decode($d->test_name, 'true') : [];
                                                    @endphp
                                                    <select name="test_name_edit[]"
                                                        id="test_name_edit_{{ $d->id }}"
                                                        class="form-control select2 test_name_edit" multiple data-id="{{ $d->id }}">
                                                        @foreach ($test as $t)
                                                            <option data-id="{{$t->fldtype }}" @if (in_array($t->fldtestid, $test_names)) selected @endif
                                                                value="{{ $t->fldtestid }}">{{ $t->fldtestid }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="test_status_edit_{{ $d->id }}">Test Status </label>
                                                    <select name="test_status_edit"
                                                        id="test_status_edit_{{ $d->id }}" class="form-control">
                                                        <option value="">Select</option>
                                                        @if ($d->test_status == 'Abnormal')
                                                            <option value="Abnormal" selected>Abnormal</option>
                                                            <option value="Normal">Normal</option>
                                                        @elseif($d->test_status == 'Normal')
                                                            <option value="Abnormal">Abnormal</option>
                                                            <option value="Normal" selected>Normal</option>
                                                        @else
                                                            <option value="Abnormal">Abnormal</option>
                                                            <option value="Normal">Normal</option>
                                                        @endif
                                                    </select>
                                                    {{-- <input type="text" id="test_status" name="test_status" value="" class="form-control" placeholder=""/> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="lab_test_details_edit_{{ $d->id }}" @if (isset($d->test_details)) @else style="display: none;" @endif>
                                    <div class="form-group">
                                        <d-flex class="flex-column">
                                            <label for="test_details_edit_{{ $d->id }}">Test Details</label>
                                            <textarea id="test_details_edit_{{ $d->id }}" name="test_details_edit" class="form-control" placeholder="Test Details">
                                            @if (isset($d->test_details))
                                            {{ $d->test_details }}
                                            @endif
                                            </textarea>
                                        </d-flex>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">SMS Text Details</label>
                                        <textarea id="sms-details-textarea-edit-{{ $d->id }}" name="sms_details_edit" class="form-control"
                                            placeholder="SMS Text Details">
                                        @if (isset($d->sms_details))
                                        {{ $d->sms_details }}
                                        @endif
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                            {{-- <a href="javascript:;" data-id="{{ $d->id }}" class="btn btn-primary update-sms-form onclose" data-dismiss="modal">Save</a> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endforeach
    <!-- END Edit SMS Modal-->

    <!--Edit Clone Modal-->
    @foreach ($resultData as $d)
        <div class="modal fade" id="clone-modal-{{ $d->id }}" tabindex="-1" role="dialog"
            aria-labelledby="ItemModal" aria-hidden="true">
            <form method="POST" id="clone-sms-form-{{ $d->id }}" class="clone-sms-form"
                data-id="{{ $d->id }}">
                <input type="hidden" name="id" value="{{ $d->id }}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Clone SMS</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-row">
                                        <label for="sms_type_clone_{{ $d->id }}">SMS Type </label>
                                        <select id="sms_type_clone_{{ $d->id }}" data-id="{{ $d->id }}"
                                            class="form-control sms_type_clone" disabled>
                                            <option value="">Select</option>
                                            @if ($d->sms_type == 'Follow Up')
                                                <option value="Follow Up" selected>Follow Up</option>
                                                <option value="Deposit">Deposit</option>
                                                <option value="Events">Events</option>
                                                <option value="Lab">Lab</option>
                                            @elseif($d->sms_type == 'Deposit')
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deposit" selected>Deposit</option>
                                                <option value="Events">Events</option>
                                                <option value="Lab">Lab</option>
                                            @elseif($d->sms_type == 'Events')
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deposit">Deposit</option>
                                                <option value="Events" selected>Events</option>
                                                <option value="Lab">Lab</option>
                                            @elseif($d->sms_type == 'Lab')
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Deposit">Deposit</option>
                                                <option value="Events">Events</option>
                                                <option value="Lab" selected>Lab</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="sms_type_clone" value="{{ $d->sms_type }}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sms_name_clone_{{ $d->id }}">SMS Name </label>
                                        <input type="text" id="sms_name_clone_{{ $d->id }}" name="sms_name_clone"
                                            value="" class="form-control" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="d-flex flex-column">
                                            <label for="status_clone_{{ $d->id }}">Status </label>
                                            <div class="d-flex flex-row">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="status_clone"
                                                        id="status_active_clone_{{ $d->id }}" value="Active"
                                                        class="custom-control-input"
                                                        @if ($d->status == 'Active') checked @endif>
                                                    <label class="custom-control-label"
                                                        for="status_active_clone_{{ $d->id }}"> Active </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" name="status_clone"
                                                        id="status_inactive_clone_{{ $d->id }}"
                                                        class="custom-control-input" value="Inactive"
                                                        @if ($d->status == 'Inactive') checked @endif>
                                                    <label class="custom-control-label"
                                                        for="status_inactive_clone_{{ $d->id }}"> Inactive</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="follow_up_day_clone_{{ $d->id }}"
                                        @if ($d->sms_type == 'Follow Up') @else style="display: none;" @endif>
                                        <div class="form-group">
                                            <label for="free_follow_up_day_clone_{{ $d->id }}">Free Followup
                                                Remaining Days </label>
                                            <input type="number" id="free_follow_up_day_clone_{{ $d->id }}"
                                                name="free_follow_up_day_clone" value="{{ $d->free_follow_up_day }}"
                                                max="{{ Options::get('followup_days') }}" class="form-control" readonly
                                                placeholder="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="deposit_clone_{{ $d->id }}"
                                    @if ($d->sms_type == 'Deposit') @else style="display: none;" @endif>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label
                                                        for="deposit_condition_clone_{{ $d->id }}">Condition</label>
                                                    <select data-id="{{ $d->id }}"
                                                        id="deposit_condition_clone_{{ $d->id }}"
                                                        class="form-control deposit_condition_clone" disabled>
                                                        <option value="">Select</option>
                                                        @if ($d->deposit_condition == 'Deposit')
                                                            <option value="Deposit" selected>Deposit</option>
                                                            <option value="Expenses">Expenses</option>
                                                        @elseif($d->deposit_condition == 'Expenses')
                                                            <option value="Deposit">Deposit</option>
                                                            <option value="Expenses" selected>Expenses</option>
                                                        @else
                                                            <option value="Deposit">Deposit</option>
                                                            <option value="Expenses">Expenses</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" name="deposit_condition_clone"
                                                value="{{ $d->deposit_condition }}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deposit_mode_clone_{{ $d->id }}">Mode </label>
                                                    <select id="deposit_mode_clone_{{ $d->id }}"
                                                        class="form-control" disabled>
                                                        <option value="">Select</option>
                                                        @if ($d->deposit_mode == '>')
                                                            <option value=">" selected>> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->deposit_mode == '<')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<" selected>
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->deposit_mode == '=')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=" selected>= Equals To</option>
                                                        @else
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" name="deposit_mode_clone" value="{{ $d->deposit_mode }}">
                                            <div class="col-md-6">
                                                <div class="form-group"
                                                    id="deposit_amount_text_clone_{{ $d->id }}"
                                                    @if (isset($d->deposit_amount)) @else style="display: none;" @endif>
                                                    <label for="deposit_amount_clone_{{ $d->id }}"></label>
                                                    <input type="text" id="deposit_amount_clone_{{ $d->id }}"
                                                        name="deposit_amount_clone" value="{{ $d->deposit_amount }}" class="form-control"
                                                        readonly placeholder="Deposit Amount" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"
                                                    id="deposit_percentage_text_clone_{{ $d->id }}"
                                                    @if (isset($d->deposit_percentage)) @else style="display: none;" @endif>
                                                    <label for="deposit_percentage_clone_{{ $d->id }}">Deposit
                                                        Percentage(%) </label>
                                                    <input type="text" id="deposit_percentage_clone_{{ $d->id }}"
                                                        name="deposit_percentage_clone"
                                                        value="{{ $d->deposit_percentage }}" class="form-control"
                                                        readonly placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="events_clone_{{ $d->id }}"
                                        @if ($d->sms_type == 'Events') @else style="display: none;" @endif>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="events_condition_clone_{{ $d->id }}">Patient Visits
                                                        Frequency </label>
                                                    <select id="events_condition_clone_{{ $d->id }}"
                                                        class="form-control" disabled>
                                                        <option value="">Select</option>
                                                        @if ($d->events_condition == '>')
                                                            <option value=">" selected>> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->events_condition == '<')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<" selected>
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @elseif($d->events_condition == '=')
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=" selected>= Equals To</option>
                                                        @else
                                                            <option value=">">> Greater Than</option>
                                                            <option value="<">
                                                                < Less Than</option>
                                                            <option value="=">= Equals To</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" name="events_condition_clone"
                                                value="{{ $d->events_condition }}">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="visit_per_year_clone_{{ $d->id }}">Number of Visits
                                                        Per Year </label>
                                                    <input type="text" id="visit_per_year_clone_{{ $d->id }}"
                                                        name="visit_per_year_clone" value="{{ $d->visit_per_year }}"
                                                        class="form-control" readonly placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="lab_clone_{{ $d->id }}"
                                        @if ($d->sms_type == 'Lab') @else style="display: none;" @endif>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="test_name_clone_{{ $d->id }}">Test Name </label>
                                                    @php
                                                        $test_names = $d->test_name ? json_decode($d->test_name, 'true') : [];
                                                    @endphp
                                                    <select name="test_name_clone[]"
                                                        id="test_name_clone_{{ $d->id }}"
                                                        class="form-control select2 test_name_clone" multiple disabled data-id="{{ $d->id }}">
                                                        @foreach ($test as $t)
                                                            <option @if (in_array($t->fldtestid, $test_names)) selected @endif
                                                                value="{{ $t->fldtestid }}">{{ $t->fldtestid }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="test_status_clone_{{ $d->id }}">Test Status
                                                    </label>
                                                    <select name="test_status_clone"
                                                        id="test_status_clone_{{ $d->id }}" class="form-control"
                                                        disabled>
                                                        <option value="">Select</option>
                                                        @if ($d->test_status == 'Abnormal')
                                                            <option value="Abnormal" selected>Abnormal</option>
                                                            <option value="Normal">Normal</option>
                                                        @elseif($d->test_status == 'Normal')
                                                            <option value="Abnormal">Abnormal</option>
                                                            <option value="Normal" selected>Normal</option>
                                                        @else
                                                            <option value="Abnormal">Abnormal</option>
                                                            <option value="Normal">Normal</option>
                                                        @endif
                                                    </select>
                                                    <input type="hidden" name="test_status_clone"
                                                        value="{{ $d->test_status }}">
                                                    {{-- <input type="text" id="test_status" name="test_status" value="" class="form-control" placeholder=""/> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="lab_test_details_clone_{{ $d->id }}" @if (isset($d->test_details)) @else style="display: none;" @endif>
                                    <div class="form-group">
                                        <d-flex class="flex-column">
                                            <label for="test_details_clone_{{ $d->id }}">Test Details</label>
                                            <textarea id="test_details_clone_{{ $d->id }}" name="test_details_clone" class="form-control" placeholder="Test Details" readonly>
                                            @if (isset($d->test_details))
                                            {{ $d->test_details }}
                                            @endif
                                            </textarea>
                                        </d-flex>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">SMS Text Detail</label>
                                        <textarea id="sms-details-textarea-clone-{{ $d->id }}" name="sms_details_clone" class="form-control"
                                            placeholder="SMS Text Details"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary onclose" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                            {{-- <a href="javascript:;" data-id="{{ $d->id }}" class="btn btn-primary clone-sms-form onclose" data-dismiss="modal">Save</a> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endforeach
    <!-- END Clone SMS Modal-->

@endsection
@push('after-script')
    <script>
        const sms_names = {!! $sms_name !!}

        const sms_type = "{{ $sms_type_search }}"

        const sms_name = "{{ $sms_name_search }}"

        $(document).ready(function() {
            if (sms_name) {
                let data = [];
                $('#sms_name_search').find('option').remove().end().append(
                    '<option value="" selected disabled>--Select--</option>').val('');
                $.each(sms_names, function(i, item) {
                    if (sms_type == item.sms_type)
                        $('#sms_name_search').append($('<option>', {
                            value: item.sms_name,
                            text: item.sms_name
                        }));
                });
                $('#sms_name_search').val(sms_name);
            }
        });


        $('#sms_type_search').change(function() {
            let data = [];
            const sms_type_search = $(this).val();
            $('#sms_name_search').find('option').remove().end().append(
                '<option value="" selected disabled>--Select--</option>').val('');
            $.each(sms_names, function(i, item) {
                if (sms_type_search == item.sms_type)
                    $('#sms_name_search').append($('<option>', {
                        value: item.sms_name,
                        text: item.sms_name
                    }));
            });
            // $('#sms_name_search').html('<option value="">Select</option>');
            // var sms_type_search = $('#sms_type_search');

            // var url = "{{ route('smssetting.searchname') }}";

            // $.ajax({
            //         url: url,
            //         type: "POST",
            //         dataType: "json",
            //         data: sms_type_search,
            //         success: function (response) {
            //             var str = "";
            //             str += '<option value="">'+"Select"+'</option>'
            //             $.each(response.smsName, function(key,value) {
            //                 str += '<option value ="'+value.sms_name+'">'+ value.sms_name +'</option>'
            //             })
            //             $('#sms_name_search').html(str);
            //         },
            //         error: function (xhr, status, error) {
            //             var errorMessage = xhr.status + ': ' + xhr.statusText;
            //             console.log(xhr);
            //         }
            //     });
        });

        $(document).on('click', '#add_sms', function() {
            $('#add_sms_modal').modal('show');
        });

        $(document).on('change', '#sms_type', function(e) {
            e.preventDefault();
            var sms_type = $('#sms_type').val();

            if (sms_type == "Follow Up") {
                $('#deposit').hide();
                $('#deposit_condition option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text').hide();
                $('#deposit_amount_text input').val('');
                $('#deposit_percentage_text').hide();
                $('#deposit_percentage_text input').val('');
                $('#deposit_mode option:nth-child(1)').prop("selected", true);
                $('#events').hide();
                $('#events_condition option:nth-child(1)').prop("selected", true);
                $('#visit_per_year').val('');
                $('#lab').hide();
                $('#test_name').val('');
                $('#test_name').trigger("change");
                $('#test_status option:nth-child(1)').prop("selected", true);
                $('#test_details').val('');
                $('#lab_test_details').hide();
                $('#follow_up_day').show();
            } else if (sms_type == "Deposit") {
                $('#follow_up_day').hide();
                $('#follow_up_day input').val('');
                $('#events').hide();
                $('#events_condition option:nth-child(1)').prop("selected", true);
                $('#visit_per_year').val('');
                $('#lab').hide();
                $('#test_name').val('');
                $('#test_name').trigger("change");
                $('#test_status option:nth-child(1)').prop("selected", true);
                $('#test_details').val('');
                $('#lab_test_details').hide();
                $('#deposit').show();
            } else if (sms_type == "Events") {
                $('#follow_up_day').hide();
                $('#follow_up_day input').val('');
                $('#deposit').hide();
                $('#deposit_condition option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text').hide();
                $('#deposit_amount_text input').val('');
                $('#deposit_percentage_text').hide();
                $('#deposit_percentage_text input').val('');
                $('#deposit_mode option:nth-child(1)').prop("selected", true);
                $('#lab').hide();
                $('#test_name').val('');
                $('#test_name').trigger("change");
                $('#test_status option:nth-child(1)').prop("selected", true);
                $('#test_details').val('');
                $('#lab_test_details').hide();
                $('#events').show();
            } else if (sms_type == "Lab") {
                $('#follow_up_day').hide();
                $('#follow_up_day input').val('');
                $('#deposit').hide();
                $('#deposit_condition option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text').hide();
                $('#deposit_amount_text input').val('');
                $('#deposit_percentage_text').hide();
                $('#deposit_percentage_text input').val('');
                $('#deposit_mode option:nth-child(1)').prop("selected", true);
                $('#events').hide();
                $('#events_condition option:nth-child(1)').prop("selected", true);
                $('#visit_per_year').val('');
                $('#lab').show();
            } else {
                $('#deposit').hide();
                $('#deposit_condition option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text').hide();
                $('#deposit_amount_text input').val('');
                $('#deposit_percentage_text').hide();
                $('#deposit_percentage_text input').val('');
                $('#deposit_mode option:nth-child(1)').prop("selected", true);
                $('#events').hide();
                $('#events_condition option:nth-child(1)').prop("selected", true);
                $('#visit_per_year').val('');
                $('#lab').hide();
                $('#test_name').val('');
                $('#test_name').trigger("change");
                $('#test_status option:nth-child(1)').prop("selected", true);
                $('#test_details').val('');
                $('#lab_test_details').hide();
                $('#follow_up_day').hide();
                $('#follow_up_day input').val('');
            }
        });

        $(document).on('change', '#deposit_condition', function(e) {
            e.preventDefault();
            var deposit_condition = $('#deposit_condition').val();

            if (deposit_condition == "Deposit") {
                $('#deposit_amount_text').hide();
                $('#deposit_amount_text input').val('');
                $('#deposit_percentage_text').show();
            } else if (deposit_condition == "Expenses") {
                $('#deposit_percentage_text').hide();
                $('#deposit_percentage_text input').val('');
                $('#deposit_amount_text').show();
                $('#deposit_amount_text input').val('Deposit Amount');
            } else {
                $('#deposit_percentage_text').hide();
                $('#deposit_percentage_text input').val('');
                $('#deposit_amount_text').hide();
                $('#deposit_amount_text input').val('');
            }
        });

        $(document).on('change', '#test_name', function(e) {
            e.preventDefault();
            let result = $("#test_name option:selected").map(function() {
                return $(this).data("id");
            }).get();
            if(jQuery.inArray("Quantitative", result) !== -1)
            {
                $('#test_details').val('');
                $('#lab_test_details').show();
            }
            else{
                $('#test_details').val('');
                $('#lab_test_details').hide();
            }
        });



        $('#add-sms-form').submit(function(e) {
            e.preventDefault();
            $('.loader-ajax-start-stop-container').show();
            // var datas = ($('#add-sms-form').serialize());
            var sms_type = $('#sms_type').val();

            if (sms_type === '') {
                showAlert('Please Select SMS Type', 'error');
                return false;
            }

            var url = "{{ route('smssetting.savesms') }}";
            $.ajax({
                url: url,
                type: "POST",
                data: $('#add-sms-form').serialize(),
                success: function(response) {
                    if (response.status == true) {
                        // $('#sms_type option:nth-child(1)').prop("selected", true);
                        // $('#sms_name').val('');
                        // $('#sms-details-textarea').val('');

                        // $('#deposit').hide();
                        // $('#deposit_condition option:nth-child(1)').prop("selected", true);
                        // $('#deposit_amount_text').hide();
                        // $('#deposit_amount_text input').val('');
                        // $('#deposit_percentage_text').hide();
                        // $('#deposit_percentage_text input').val('');
                        // $('#deposit_mode option:nth-child(1)').prop("selected", true);
                        // $('#events').hide();
                        // $('#events_condition option:nth-child(1)').prop("selected",true);
                        // $('#visit_per_year').val('');
                        // $('#lab').hide();
                        // $('#test_name').val('');
                        // $('#test_name').trigger("change");
                        // $('#test_status option:nth-child(1)').prop("selected",true);
                        // $('#follow_up_day').hide();
                        // $('#follow_up_day input').val('');
                        showAlert(response.message);
                        $('#smslist').empty().append(response.view.html)
                        $('#add_sms_modal').modal('hide');
                        location.reload();
                        
                    } else {
                        showAlert(response.message);
                    }
                }
            });
        });

        $('.edit-sms-form').submit(function(e) {
            e.preventDefault();
            $('.loader-ajax-start-stop-container').show();
            var id = $(this).data('id');

            var datas = ($('#edit-sms-form-' + id).serialize());

            var sms_type = $('#sms_type_edit_' + id).val();

            if (sms_type === '') {
                showAlert('Please Select SMS Type', 'error');
                return false;
            }

            var url = "{{ route('smssetting.updatesms') }}";
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: datas,
                success: function(response) {
                    if (response.status == true) {
                        showAlert(response.message);
                        $('#smslist').empty().append(response.view.html)
                        $('#edit-modal-' + id).modal('hide');
                        location.reload();
                    } else {
                        showAlert(response.message);
                    }
                }
            });
        });

        $(document).on('change', '.sms_type_edit', function() {
            var id = $(this).data('id');
            var sms_type = $('#sms_type_edit_' + id).val();

            if (sms_type == "Follow Up") {
                $('#deposit_edit_' + id).hide();
                $('#deposit_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_edit_' + id).hide();
                $('#deposit_amount_text_edit_' + id + ' input').val('');
                $('#deposit_percentage_text_edit_' + id).hide();
                $('#deposit_percentage_text_edit_' + id + ' input').val('');
                $('#deposit_mode_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#events_edit_' + id).hide();
                $('#events_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_edit_' + id).val('');
                $('#lab_edit_' + id).hide();
                $('#test_name_edit_' + id).val('');
                $('#test_name_edit_' + id).trigger("change");
                $('#test_status_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_edit_' + id).val('');
                $('#lab_test_details_edit_' + id).hide();
                $('#follow_up_day_edit_' + id).show();
            } else if (sms_type == "Deposit") {
                $('#follow_up_day_edit_' + id).hide();
                $('#follow_up_day_edit_' + id + ' input').val('');
                $('#events_edit_' + id).hide();
                $('#events_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_edit_' + id).val('');
                $('#lab_edit_' + id).hide();
                $('#test_name_edit_' + id).val('');
                $('#test_name_edit_' + id).trigger("change");
                $('#test_status_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_edit_' + id).val('');
                $('#lab_test_details_edit_' + id).hide();
                $('#deposit_edit_' + id).show();
            } else if (sms_type == "Events") {
                $('#follow_up_day_edit_' + id).hide();
                $('#follow_up_day_edit_' + id + ' input').val('');
                $('#deposit_edit_' + id).hide();
                $('#deposit_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_edit_' + id).hide();
                $('#deposit_amount_text_edit_' + id + ' input').val('');
                $('#deposit_percentage_text_edit_' + id).hide();
                $('#deposit_percentage_text_edit_' + id + ' input').val('');
                $('#deposit_mode_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#lab_edit_' + id).hide();
                $('#test_name_edit_' + id).val('');
                $('#test_name_edit_' + id).trigger("change");
                $('#test_status_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_edit_' + id).val('');
                $('#lab_test_details_edit_' + id).hide();
                $('#events_edit_' + id).show();
            } else if (sms_type == "Lab") {
                $('#follow_up_day_edit_' + id).hide();
                $('#follow_up_day_edit_' + id + ' input').val('');
                $('#deposit_edit_' + id).hide();
                $('#deposit_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_edit_' + id).hide();
                $('#deposit_amount_text_edit_' + id + ' input').val('');
                $('#deposit_percentage_text_edit_' + id).hide();
                $('#deposit_percentage_text_edit_' + id + ' input').val('');
                $('#deposit_mode_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#events_edit_' + id).hide();
                $('#events_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_edit_' + id).val('');
                $('#lab_edit_' + id).show();
            } else {
                $('#deposit_edit_' + id).hide();
                $('#deposit_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_edit_' + id).hide();
                $('#deposit_amount_text_edit_' + id + ' input').val('');
                $('#deposit_percentage_text_edit_' + id).hide();
                $('#deposit_percentage_text_edit_' + id + ' input').val('');
                $('#deposit_mode_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#events_edit_' + id).hide();
                $('#events_condition_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_edit_' + id).val('');
                $('#lab_edit_' + id).hide();
                $('#test_name_edit_' + id).val('');
                $('#test_name_edit_' + id).trigger("change");
                $('#test_status_edit_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_edit_' + id).val('');
                $('#lab_test_details_edit_' + id).hide();
                $('#follow_up_day_edit_' + id).hide();
                $('#follow_up_day_edit_' + id + ' input').val('');
            }
        });

        $(document).on('change', '.deposit_condition_edit', function(e) {
            var id = $(this).data('id');
            var deposit_condition = $('#deposit_condition_edit_' + id).val();

            if (deposit_condition == "Deposit") {
                $('#deposit_amount_text_edit_' + id).hide();
                $('#deposit_amount_text_edit_' + id + ' input').val('');
                $('#deposit_percentage_text_edit_' + id).show();
            } else if (deposit_condition == "Expenses") {
                $('#deposit_percentage_text_edit_' + id).hide();
                $('#deposit_percentage_text_edit_' + id + ' input').val('');
                $('#deposit_amount_text_edit_' + id).show();
                $('#deposit_amount_text_edit_' + id + ' input').val('Deposit Amount');
            } else {
                $('#deposit_percentage_text_edit_' + id).hide();
                $('#deposit_percentage_text_edit_' + id + ' input').val('');
                $('#deposit_amount_text_edit_' + id).hide();
                $('#deposit_amount_text_edit_' + id + ' input').val('');
            }
        });

        $(document).on('change', '.test_name_edit', function(e) {
            var id = $(this).data('id');
            
            let result = $('#test_name_edit_' + id + ' option:selected').map(function() {
                return $(this).data("id");
            }).get();
            
            if(jQuery.inArray("Quantitative", result) !== -1)
            {
                $('#test_details_edit_' + id).val('');
                $('#lab_test_details_edit_' + id).show();
            }
            else{
                $('#test_details_edit_' + id).val('');
                $('#lab_test_details_edit_' + id).hide();
            }
        });

        $('.clone-sms-form').submit(function(e) {
            e.preventDefault();
            // $(".sms_type_clone").on('click',function(event) {
            //     event.preventDefault();
            // });
            var id = $(this).data('id');

            var datas = ($('#clone-sms-form-' + id).serialize());

            var sms_type = $('#sms_type_clone_' + id).val();

            if (sms_type === '') {
                showAlert('Please Select SMS Type', 'error');
                return false;
            }

            var url = "{{ route('smssetting.clonesms') }}";
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: datas,
                success: function(response) {
                    if (response.status == true) {
                        // $('.loader-ajax-start-stop-container').show();
                        showAlert(response.message);
                        $('#smslist').empty().append(response.view.html)
                        $('#clone-modal-' + id).modal('hide');
                        location.reload();
                    } else {
                        $('.loader-ajax-start-stop-container').show();
                        showAlert(response.message);
                    }
                }
            });
        });

        $(document).on('change', '.sms_type_clone', function() {
            var id = $(this).data('id');
            var sms_type = $('#sms_type_clone_' + id).val();

            if (sms_type == "Follow Up") {
                $('#deposit_clone_' + id).hide();
                $('#deposit_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_clone_' + id).hide();
                $('#deposit_amount_text_clone_' + id + ' input').val('');
                $('#deposit_percentage_text_clone_' + id).hide();
                $('#deposit_percentage_text_clone_' + id + ' input').val('');
                $('#deposit_mode_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#events_clone_' + id).hide();
                $('#events_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_clone_' + id).val('');
                $('#lab_clone_' + id).hide();
                $('#test_name_clone_' + id).val('');
                $('#test_name_clone_' + id).trigger("change");
                $('#test_status_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_clone_' + id).val('');
                $('#lab_test_details_clone_' + id).hide();
                $('#follow_up_day_clone_' + id).show();
            } else if (sms_type == "Deposit") {
                $('#follow_up_day_clone_' + id).hide();
                $('#follow_up_day_clone_' + id + ' input').val('');
                $('#events_clone_' + id).hide();
                $('#events_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_clone_' + id).val('');
                $('#lab_clone_' + id).hide();
                $('#test_name_clone_' + id).val('');
                $('#test_name_clone_' + id).trigger("change");
                $('#test_status_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_clone_' + id).val('');
                $('#lab_test_details_clone_' + id).hide();
                $('#deposit_clone_' + id).show();
            } else if (sms_type == "Events") {
                $('#follow_up_day_clone_' + id).hide();
                $('#follow_up_day_clone_' + id + ' input').val('');
                $('#deposit_clone_' + id).hide();
                $('#deposit_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_clone_' + id).hide();
                $('#deposit_amount_text_clone_' + id + ' input').val('');
                $('#deposit_percentage_text_clone_' + id).hide();
                $('#deposit_percentage_text_clone_' + id + ' input').val('');
                $('#deposit_mode_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#lab_clone_' + id).hide();
                $('#test_name_clone_' + id).val('');
                $('#test_name_clone_' + id).trigger("change");
                $('#test_status_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_clone_' + id).val('');
                $('#lab_test_details_clone_' + id).hide();
                $('#events_clone_' + id).show();
            } else if (sms_type == "Lab") {
                $('#follow_up_day_clone_' + id).hide();
                $('#follow_up_day_clone_' + id + ' input').val('');
                $('#deposit_clone_' + id).hide();
                $('#deposit_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_clone_' + id).hide();
                $('#deposit_amount_text_clone_' + id + ' input').val('');
                $('#deposit_percentage_text_clone_' + id).hide();
                $('#deposit_percentage_text_clone_' + id + ' input').val('');
                $('#deposit_mode_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#events_clone_' + id).hide();
                $('#events_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_clone_' + id).val('');
                $('#lab_clone_' + id).show();
            } else {
                $('#deposit_clone_' + id).hide();
                $('#deposit_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#deposit_amount_text_clone_' + id).hide();
                $('#deposit_amount_text_clone_' + id + ' input').val('');
                $('#deposit_percentage_text_clone_' + id).hide();
                $('#deposit_percentage_text_clone_' + id + ' input').val('');
                $('#deposit_mode_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#events_clone_' + id).hide();
                $('#events_condition_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#visit_per_year_clone_' + id).val('');
                $('#lab_clone_' + id).hide();
                $('#test_name_clone_' + id).val('');
                $('#test_name_clone_' + id).trigger("change");
                $('#test_status_clone_' + id + ' option:nth-child(1)').prop("selected", true);
                $('#test_details_clone_' + id).val('');
                $('#lab_test_details_clone_' + id).hide();
                $('#follow_up_day_clone_' + id).hide();
                $('#follow_up_day_clone_' + id + ' input').val('');
            }
        });

        $(document).on('change', '.deposit_condition_clone', function(e) {
            var id = $(this).data('id');
            var deposit_condition = $('#deposit_condition_clone_' + id).val();

            if (deposit_condition == "Deposit") {
                $('#deposit_amount_text_clone_' + id).hide();
                $('#deposit_amount_text_clone_' + id + ' input').val('');
                $('#deposit_percentage_text_clone_' + id).show();
            } else if (deposit_condition == "Expenses") {
                $('#deposit_percentage_text_clone_' + id).hide();
                $('#deposit_percentage_text_clone_' + id + ' input').val('');
                $('#deposit_amount_text_clone_' + id).show();
                $('#deposit_amount_text_clone_' + id + ' input').val('Deposit Amount');
            } else {
                $('#deposit_percentage_text_clone_' + id).hide();
                $('#deposit_percentage_text_clone_' + id + ' input').val('');
                $('#deposit_amount_text_clone_' + id).hide();
                $('#deposit_amount_text_clone_' + id + ' input').val('');
            }
        });

        $(document).on('change', '.test_name_clone', function(e) {
            var id = $(this).data('id');
            
            let result = $('#test_name_clone_' + id + ' option:selected').map(function() {
                return $(this).data("id");
            }).get();
            
            if(jQuery.inArray("Quantitative", result) !== -1)
            {
                $('#test_details_clone_' + id).val('');
                $('#lab_test_details_clone_' + id).show();
            }
            else{
                $('#test_details_clone_' + id).val('');
                $('#lab_test_details_clone_' + id).hide();
            }
        });

        $('#reset_sms').click(function() {
            // location.reload();
            $('.loader-ajax-start-stop-container').show();
        });

        $('#refresh').click(function() {
            $('.loader-ajax-start-stop-container').show();
        });

        $(function() {
            $('#myTable1').bootstrapTable()
        })

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var self = $(this);
            var id = self.attr('id');
            if (confirm('Are you sure to delete?')) {
                $("#delete_form_" + id).submit();
                $('.loader-ajax-start-stop-container').show();
            }
        });
    </script>
@endpush
