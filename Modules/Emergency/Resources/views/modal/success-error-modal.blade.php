@if(Session::has('display_popup_error_success') && Session::get('display_popup_error_success'))
    <div class="modal fade" id="success-error" tabindex="-1" role="dialog" aria-labelledby="success-errorLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="success-errorLabel">
                        @if(Session::has('success_message'))
                            Success
                        @endif
                        @if(Session::has('error_message'))
                            Error
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(Session::has('success_message'))
                        <p class="text-success">{{ Session::get('success_message') }}</p>
                    @endif
                    @if(Session::has('error_message'))
                        <p class="text-danger">{{ Session::get('error_message') }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
