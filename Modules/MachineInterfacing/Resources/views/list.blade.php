@extends('frontend.layouts.master')

@section('content')
    <div class="container-fluid extra-fluid">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close text-black-50 float-right" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">

                <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                    <div class="iq-card-header d-flex justify-content-between align-items-center">
                        <div class="iq-header-title">
                            <h4 class="card-title">Machine Map List</h4>
                        </div>
                        <a href="{{ route('machine.interfacing.add') }}" class="btn btn-sm bg-success"><i
                                    class="ri-add-fill"><span class="pl-1">Add New</span></i>
                        </a>
                    </div>
                    <div class="iq-card-body">
                        <div class="res-table">
                            <table class="table table-striped table-bordered table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>SNo</th>
                                    <th>Code</th>
                                    <th>Machine Name</th>
                                    <th>Lab Test</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($machine_map))
                                    @forelse($machine_map as $map)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $map->code }}</td>
                                            <td>{{ $map->machinename }}</td>
                                            <td>{{ $map->test }}</td>
                                            <td>
                                                <a href="{{ route('machine.interfacing.edit', $map->id) }}"><i class="fas fa-edit text-primary"></i></a>
                                                |
                                                <a href="{{ route('machine.interfacing.delete', $map->id) }}" onclick="return confirm('Delete?')"><i class="fas fa-trash text-danger"></i></a>
                                            </td>
                                        </tr>
                                    @empty

                                    @endforelse
                                @endif
                                </tbody>
                            </table>

                        </div>
                        <br>
                        <div class="form-group padding-none">
                            <div class="form-inner">
                                {{ $machine_map->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
