
<!DOCTYPE html>
<html>
<head>
<title>Hospital Log Dashboard</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script
	src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet"
	href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet"
	href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<script
	src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<style>
    .loader-ajax-start-stop {
        position: absolute;
        left: 45%;
        top: 35%;
    }

    .loader-ajax-start-stop-container {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background: black;
        opacity: .5;
        z-index: 1051;
    }
</style>
<body style="background-color: #F5F5F5;">
    <div class="loader-ajax-start-stop-container">
        <div class="loader-ajax-start-stop">
            <img src="{{ asset('images/loader.svg') }}">
        </div>
    </div>
    <nav class="navbar  navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- {{ config('app.name', 'Laravel') }} -->
                    <img src="/images/cogent.png" alt="Cogenthealth"   height="25" style="float:left"> &nbsp
                    Cogent Health Pvt. Ltd
                </a>
            </div>
            {{-- <ul class="nav navbar-nav navbar-right">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest                            
            </ul>				 --}}
        </div>
    </nav>
	<div class="container">
			<h3 style="text-align:center;">
				Cogent Support
			</h3>
		<div class="card"  style="background-color: #FFFFFF; padding:10px;border-radius: 10px;box-shadow: 5px 5px  #888888;">
            {{ csrf_field() }}
            <ol>
                @foreach($logData as $key_hospital => $hospital)
                <li class="hospitalLabel" data-hospital="{{ $key_hospital }}">{!! $key_hospital !!}</li>
                    <ol type="a" data-hospital="{{ $key_hospital }}" class="departmentNames">    
                        @foreach ($hospital as $key_department => $department)
                            <li data-hospital="{{ $key_hospital }}" data-department="{{ $key_department }}" class="departmentLabel">{!! $key_department !!}</li>
                            <div class="table-responsive text-center table_log" data-hospital="{{ $key_hospital }}" data-department="{{ $key_department }}" data-loaded="false">
                            </div>
                        @endforeach
                    </ol>
                @endforeach
            </ol>
		</div>
	</div>
</body>
<script>
/*ajax loader*/
var $loadingContainer = $('.loader-ajax-start-stop-container').hide();

$('#loader-ajax-start-stop').show();
$loadingContainer.show();
$(document).ready(function () {
    $('#loader-ajax-start-stop').hide();
    $loadingContainer.hide();
})

$(document)
    .ajaxStart(function () {
        $('#loader-ajax-start-stop').show();
        $loadingContainer.show();
    })
    .ajaxStop(function () {
        $('#loader-ajax-start-stop').hide();
        $loadingContainer.hide();
    });

$(document).ready(function() {
    $('.departmentNames').hide();
    $('.table_log').hide();
} );

$(document).on('click','.hospitalLabel',function(){
    if($('.departmentNames[data-hospital="'+$(this).attr('data-hospital')+'"]').hasClass('show')){
        $('.departmentNames[data-hospital="'+$(this).attr('data-hospital')+'"]').hide();
        $('.departmentNames[data-hospital="'+$(this).attr('data-hospital')+'"]').removeClass("show");
    }else{
        $('.departmentNames[data-hospital="'+$(this).attr('data-hospital')+'"]').show();
        $('.departmentNames[data-hospital="'+$(this).attr('data-hospital')+'"]').addClass("show");
    }
});

$(document).on('click','.departmentLabel',function(){
    var tableLog = $('.table_log[data-hospital="'+$(this).attr('data-hospital')+'"][data-department="'+$(this).attr('data-department')+'"]');
    if(tableLog.hasClass("show")){
        tableLog.hide();
        tableLog.removeClass("show");
    }else{
        if(tableLog.attr("data-loaded") == "false"){
            $.ajax({
                url: '{{ route("cogent.support.getLog") }}',
                type: "POST",
                data: {
                    hospitalName: $(this).attr('data-hospital'),
                    departmentName: $(this).attr('data-department'),
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    tableLog.append(response.success.logData);
                    tableLog.attr("data-loaded","true");
                    $('.table').DataTable();
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    console.log(xhr);
                }
            });
        }
        tableLog.show();
        tableLog.addClass("show");
    }
});
</script>
</html>
