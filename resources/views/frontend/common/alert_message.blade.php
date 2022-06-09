@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(Session::get('success_message'))
    <div class="alert alert-success containerAlert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        {{ Session::get('success_message') }}
    </div>
@endif

@if(Session::get('error_message'))
    <div class="alert alert-success containerAlert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        {{ Session::get('error_message') }}
    </div>
@endif

@if(Session::get('success_message_special'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span
            class="sr-only">Close</span></button>
    {!! Session::get('success_message_special') !!}
</div>
@endif
