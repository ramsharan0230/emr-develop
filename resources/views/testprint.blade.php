<button class="print">Print now </button>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/print.js') }}"></script>
<script>
    $(document).on('click', '.print', function(){

        $.PrintPlugin({

        // selector : 'null
        remotefetch: {
            loadFormRemote : true,
            requestType : "get",
            origin : "{{ route('testprintxx.js') }}",
            // responseProperty : 'printview',
            responseProperty : null,
            payload : {

                '_token' : "{{ csrf_token() }}"
            },
        },
        print: function () {
            // console.log(" i ma printing now");
        }
        });
    

    })
    </script>