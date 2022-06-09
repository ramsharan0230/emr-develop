<!-- <a type="button" id="btn" class="btn btn-primary text-white" onclick="toggleSideBar(this)" title="Hide"><i class="fa fa-bars" aria-hidden="true"></i></a> -->

@push('after-script')
<script>
    function toggleSideBar(currelem) {
        var value = $(currelem).attr('value') || 'show';
        if (value == 'show') {
            $('.leftdiv').hide();
            $('.rightdiv').removeClass('col-lg-8').addClass('col-lg-12');
            $(currelem).removeClass('btn-primary').addClass('btn-secondary');
            $(currelem).attr('value', 'hide');
            $(currelem).attr('title', 'Show');
        } else {
            $('.leftdiv').show();
            $('.rightdiv').removeClass('col-lg-12').addClass('col-lg-7');
            $(currelem).removeClass('btn-secondary').addClass('btn-primary');
            $(currelem).attr('value', 'show');
            $(currelem).attr('title', 'Hide');
        }
    }
</script>
@endpush
