
@if ($resultData)
    @foreach ($resultData as $d)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $d->sms_type }}</td>
            <td>{{ $d->sms_name }}</td>
            <td>{{ $d->status }}</td>
            @php
                if ($d->sms_type == 'Follow Up') {
                    $sms_type = 'Free Followup Remaining Days:' . ' ' . $d->free_follow_up_day;
                } elseif ($d->sms_type == 'Deposit' && $d->deposit_condition == 'Deposit') {
                    $sms_type = $d->deposit_condition . ' ' . $d->deposit_mode . ' ' . $d->deposit_percentage . '' . '%';
                } elseif ($d->sms_type == 'Deposit' && $d->deposit_condition == 'Expenses') {
                    $sms_type = $d->deposit_condition . ' ' . $d->deposit_mode . ' ' . $d->deposit_amount;
                } elseif ($d->sms_type == 'Events') {
                    $sms_type = 'Patient Visits Frequency' . ' ' . $d->events_condition . ' ' . $d->visit_per_year;
                } else {
                    $sms_type = $d->test_status;
                }
            @endphp
            <td>{{ $sms_type }}</td>
            <td>{{ $d->sms_details }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="javascript:void(0)"
                            data-toggle="modal"
                            data-target="#view-modal-{{ $d->id }}"><i
                                class="fa fa-eye"></i>&nbsp;View Message</a>
                        <a class="dropdown-item" href="javascript:void(0)"
                            data-toggle="modal"
                            data-target="#edit-modal-{{ $d->id }}"><i
                                class="fa fa-edit"></i>&nbsp;Edit Message</a>
                        <a href="{{ route('smssetting.delete', $d->id) }}"
                            id="{{ $d->id }}" class="dropdown-item delete">
                            <i class="fa fa-trash"></i>&nbsp;Delete Message</a>
                        <form action="{{ route('smssetting.delete', $d->id) }}"
                            method="post" id="delete_form_{{ $d->id }}"
                            style="display:none;">
                            <input class="btn btn-danger" type="submit"
                                value="Delete" />
                            @method('DELETE')@csrf
                        </form>
                        <a class="dropdown-item" href="javascript:void(0)"
                            data-toggle="modal"
                            data-target="#clone-modal-{{ $d->id }}"><i
                                class="fa fa-clone"></i>&nbsp;Clone Message</a>
                    </div>
            </td>
        </tr>
    @endforeach
@endif