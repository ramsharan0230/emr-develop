@extends('frontend.layouts.master')
@section('content')
    <section class="cogent-nav">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="outPatient" data-toggle="tab" href="#out-patient" role="tab"
                   aria-controls="home" aria-selected="true"><span></span>Patient Reports / Visit Report</a>
            </li>
        </ul>

        <div class="patient-profile">
            <div class="container-fluid">
                <table class="table  table-bordered adminMgmtTable">
                    <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Title</th>
                        <th>Operation</th>
                    </tr>
                    </thead>

                    <tbody>

                    @if( count($email_templates) > 0 )
                        <?php $i = 1; ?>
                        @foreach($email_templates as $em)
                            <tr>
                                <td align="center">{{ $i++ }} </td>
                                <td>{{ $em->title }}</td>
                                <td>
                                    <a href="{{ route('admin.emailtemplate.edit',[$em->id]) }}" class="btn btn-info adminMgmtTableBtn" title="Edit Email Template"><i class="fa fa-edit"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td align="center" colspan="10">
                                Records not found. &nbsp;
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </section>


@stop

@push('after-script')

@endpush
