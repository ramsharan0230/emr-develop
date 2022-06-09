<input type="text" class="form-control mb-2" id="search-menu-input" placeholder="Search Menu" autocomplete="off">
@push('after-script')
<script>
    $("#search-menu-input").on('keyup', function(event) {
        searchMenu();
    });

    function searchMenu() {
        // Declare variables
        var input, filter, ul, li, a, i;
        input = document.getElementById("search-menu-input");
        filter = input.value.toUpperCase();
        ul = document.getElementById("iq-sidebar-toggle");
        li = ul.getElementsByTagName("li");
        if (filter == "") {
            $("#search-menu-input").next().removeClass('filtered');
            $("ul.iq-submenu").removeClass('show');
            $("li").css('display', "inherit");
            return;
        }
        $("#search-menu-input").next().addClass('filtered');

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            let parent_li = $(a).closest('ul.iq-submenu').closest('li');
            if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                parent_li.css('display', '');
                parent_li.find('ul.iq-submenu').addClass('show');
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script>
@endpush