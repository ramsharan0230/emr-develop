@extends('inpatient::pdf.layout.main')

@section('title', 'GROUP LISTS')

@section('content')
    <table style="width: 100%;"  class="content-body">
        <thead>
            <tr>
                <th>Category</th>
                <th>Particulars</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                @php
                    $groupData = \App\ReportGroup::select('fldid','flditemtype','flditemname')
                                            ->where('fldgroup',$group->fldgroup)
                                            ->orderBy('flditemname','asc')
                                            ->get()
                                            ->groupBy('flditemtype');
                @endphp
                <tr>
                   <td colspan="3">{{$group->fldgroup}}</td> 
                </tr>
                @foreach($groupData as $items)
                    @foreach($items as $item)
                        @php
                        $itemDetail = \App\ServiceCost::select('flditemcost')
                                                ->where('flditemname',$item->flditemname)
                                                ->where('flditemtype','like',$item->flditemtype)
                                                ->first();
                        @endphp
                        <tr>
                            <td>{{$item->flditemtype}}</td>
                            <td>{{$item->flditemname}}</td>
                            <td>{{(isset($itemDetail)) ? $itemDetail->flditemcost : "0"}}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection