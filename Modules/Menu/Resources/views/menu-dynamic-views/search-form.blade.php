<div class="modal fade" id="search-modal">
    <div class="modal-dialog" id="size">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="search-modal-title"></h4>
                <button type="button" class="searchclose" aria-hidden="true">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="search-form-container">
                    <div class="search-form-data"></div>
                </div>

            </div>
            <i class="glyphicon glyphicon-chevron-left"></i>
            <!-- Modal footer -->
            {{--<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>--}}

        </div>
    </div>
</div>


    @csrf
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group form-row align-items-center">
                <lable class="col-sm-3">Name</lable>
                <div class="col-sm-9">
                    <input type="text" name="" id="" palceholder="Name" class="form-control">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <lable class="col-sm-3">Address</lable>
                <div class="col-sm-9">
                    <input type="text" name="" id="" palceholder="Address" class="form-control">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <lable class="col-sm-3">Contact</lable>
                <div class="col-sm-9">
                    <input type="text" name="" id="" palceholder="Contact" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group form-row align-items-center">
                <lable class="col-sm-3">SurName</lable>
                <div class="col-sm-9">
                    <input type="text" name="" id="" palceholder="SurName" class="form-control">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <lable class="col-sm-3">District</lable>
                <div class="col-sm-9">
                    <input type="text" name="" id="" palceholder="District" class="form-control">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <lable class="col-sm-3">Gender</lable>
                <div class="col-sm-9">
                    <select name="" id="" class="form-control">
                        <option value="0">---select---</option>
                        <option value="0">Male</option>
                        <option value="0">Female</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <button class="btn btn-primary" onclick="fileSearch.search()"><i class="ri-search-2-line"></i> Search</button>
            <button class="btn btn-primary" onclick="exportSearchDataPdf()"><i class="ri-file-text-fill"></i> Export</button>
        </div>
    </div>

<hr>
<div class="res-table">
    <table class="table table-bordered table-hover table-striped">
        <thead class="thead-light">
            <tr>
                <th>PatientNo</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Address</th>
                <th>District</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="search-result"></tbody>
    </table>
</div>

<script>
    var fileSearch = {
        search: function () {
            // file-menu-search
            $.ajax({
                url: '{{ route('patient.file.menu.search.result') }}',
                type: "POST",
                data: $('.file-menu-search').serialize(),
                success: function (response) {
                    console.log(response);

                    $('.search-result').html(response);
                    // $('.file-menu-search')[0].reset();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
    }

    function displayPatientImage(val){
            // alert(val);
            // $('#file-modal').modal('hide');
             $.ajax({
                url: '{{ route('display.patient.image') }}',
                type: "POST",
                data: {fldpatientval:val, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.search-form-data').empty();
                    $('.search-modal-title').text('Patient Image');
                    $('#search-modal').modal('show');
                    $('.search-form-data').html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });

        }



        function lastEncounter(val){
             $.ajax({
                url: '{{ route('display.last.encounter') }}',
                type: "POST",
                data: {fldpatientval:val, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.search-form-data').empty();
                    $('.search-modal-title').text('Last Encounter');
                    $('#search-modal').modal('show');
                    $('.search-form-data').html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
        function lastAllEncounter(val){
             $.ajax({
                url: '{{ route('display.all.encounter') }}',
                type: "POST",
                data: {fldpatientval:val, "_token": "{{ csrf_token() }}"},
                success: function (response) {
                    // console.log(response);
                    $('.search-form-data').empty();
                    $('.search-modal-title').text('All Encounter');
                    $('#search-modal').modal('show');
                    $('.search-form-data').html(response);

                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }

        $('.searchclose').on('click', function(){
            $('#search-modal').modal('hide');
        });

        function exportSearchDataPdf(){
            $.ajax({
                url: '{{ route('patient.file.menu.search.result.pdf') }}',
                type: "POST",
                data: $('.file-menu-search').serialize(),
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response, status, xhr) {
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');

                    if (disposition) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    }
                    var linkelem = document.createElement('a');
                    try {
                        var blob = new Blob([response], {type: 'application/octet-stream'});

                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);

                            if (filename) {
                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");

                                // safari doesn't support this yet
                                if (typeof a.download === 'undefined') {
                                    window.location = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.target = "_blank";
                                    a.click();
                                }
                            } else {
                                window.location = downloadUrl;
                            }
                        }

                    } catch (ex) {
                        console.log(ex);
                    }
                    // $('.file-menu-search')[0].reset();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
</script>
