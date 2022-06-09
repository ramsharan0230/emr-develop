@extends('frontend.layouts.master')

@section('content')
    <style>
        .center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <div class="container">

        <div class="content center">

            <div class="row">
                <form action="{{ route('machine.interfacing.post') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="machine_interfacing">Data File</label>
                        <input type="file" name="machine_interfacing" id="machine_interfacing">
                    </div>
                    <div class="form-group">
                        <label for="machine_interfacing_name">Machine Name</label>
                        <select name="machine_interfacing_name" id="machine_interfacing_name">
                            <option value=""></option>
                            <option value="Biochem_sysmex">Biochem_sysmex</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>

    </div>

@stop
