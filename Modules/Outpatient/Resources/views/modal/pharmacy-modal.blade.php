<div class="modal fade" id="pharmacy-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Pharmacy Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="pharmacy-form-data"></div>

            </div>
            <i class="glyphicon glyphicon-chevron-left"></i>
            <!-- Modal footer -->
            {{--<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>--}}

        </div>
    </div>
</div>

@include('outpatient::modal.pharmacy-new-order-modal')
