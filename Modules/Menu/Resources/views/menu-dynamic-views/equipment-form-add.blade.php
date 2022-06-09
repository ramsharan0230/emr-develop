@php
    $count = 1;
@endphp
@if(count($equipmentWaiting))
    @foreach($equipmentWaiting as $con)
        <tr>
            <td>{{ $con->flditem }}</td>
            <td><span class="firstTime-{{ $count }}">{{ $con->fldfirsttime }}</span>
                <a href="javascript:;" onclick="equipmentMenu.equipmentStartInsert('{{ $con->fldid }}')" class="{{ $con->fldfirstsave == 1 ?'isDisabled':'' }}">
                    <i style="color:green" class="fas fa-play"></i>
                </a>
            </td>
            <td><span class="secondTime-{{ $count }}">{{ $con->fldsecondtime }}</span>
                <a href="javascript:;" onclick="equipmentMenu.equipmentStop('{{ $con->fldid }}')" class="{{ $con->fldfirstsave == 0 ?'isDisabled':'' }}">
                    <i style="color:red" class="fas fa-square"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
@if(count($serviceCost))
    @foreach($serviceCost as $con)
        <tr>
            <td>{{ $con->flditemname }}</td>
            <td>
                <a href="javascript:;" onclick="equipmentMenu.equipmentStartInsert('{{ $con->flditemname }}')">
                    <i style="color:green" class="fas fa-play"></i>
                </a>
            </td>
            <td>
                <a href="javascript:;" class="isDisabled">
                    <i style="color:red" class="fas fa-square"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
