@extends('frontend.layouts.master')
@section('content')

    <div class="normalheader ">
        <div class="hpanel">
            <div class="panel-body">
                <h4 class="font-light m-b-xs">
                    HMIS Mapping Report
                </h4>
            </div>
        </div>
    </div>

{{--    <section class="form-wrapper">--}}
{{--        <div class="container">--}}
{{--            <div class="col-md-6  col-md-offset-2">--}}
{{--                <br>--}}
{{--                @if(Session::has('error_message'))--}}
{{--                    <div class="alert alert-danger col">--}}
{{--                        <strong> {{ Session::get('error_message') }}</strong>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--                @if(Session::has('success_message'))--}}
{{--                    <div class="alert alert-success">--}}
{{--                        <strong>{{ Session::get('success_message') }} </strong>--}}
{{--                    </div>--}}
{{--                    <br>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <div class="row">
        <div class="row-md-3 offset-1">
            <label>Search</label>
        </div>
        <div class="row-md-6">
            <input type="text" id="search" name="search" placeholder="search here............" class="form-control">
        </div>
    </div>
    <br>
    <div class="content">
        <div class="hpanel">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Service</th>
                            <th>Mapped with</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($mappings as $mapping)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($mapping->category) ?? null }} </td>
                                <td>{{ ucfirst($mapping->sub_category) ?? null }} </td>
                                <td>{{ ucfirst($mapping->service_name) ?? null }} </td>
                                <td>{{ ucfirst($mapping->service_value) ?? null }} </td>
                               <td align="center"> <a  href="{{ route('mapping.delete',$mapping->id) }}" class="btn btn-danger delete">
                                       <i class="fa fa-trash"></i>
                                   </a></td>
                            </tr>
                        @empty
                            <tr>

                                <td colspan="4" align="center"> No data available!! </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div id="bottom_anchor">
                    {{  $mappings->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>

        // for search in table
        $("#search").on("keyup", function() {
            var value = $(this).val();

            $("table tr").each(function (index) {
                if (!index) return;
                $(this).find("td").each(function () {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;
                });
            });
        });
    </script>

@endsection
