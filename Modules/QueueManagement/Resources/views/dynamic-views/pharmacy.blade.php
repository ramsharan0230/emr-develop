@if($lists->count() > 0)
    @foreach($lists as $k => $l)
        <tr class="{{ $l->fldinside == 1 ? 'active_tr' : '' }}">
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $l->fldencounterval }}</td>
            <td>{{ $l->encounter && $l->encounter->patientInfo?$l->encounter->patientInfo->fullname:"" }}</td>
            <td>{{ $l->fldcomp_order }}</td>
            <?php $date = date('Y-m-d', strtotime($l->fldtime_order)); ?>
            <td><?php $dateNep = Helpers::dateEngToNep_queue($date); echo $dateNep->year . '-' . $dateNep->month . '-' . $dateNep->date . ' ' . date('H:i:s', strtotime($l->fldtime_order))  ?></td>
        </tr>
    @endforeach
@endif
