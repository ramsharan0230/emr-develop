@extends('frontend.layouts.master')

@section('content')
<section class="nav_menu">

    <script type="text/javascript">
        $(document).ready(function() {
            setInterval(function() {
                location.reload(true);
                showAlert('Reloading Page');
            }, 1000);


        });
    </script>
</section>

@stop