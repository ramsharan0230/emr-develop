(function($){
    let delayTimer = 500;
    let paginated = false;
    $.searchAjax = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;

        // Add a reverse reference to the DOM object
        base.$el.data("searchAjax", base);

        base.init = function(){
            base.options = $.extend({},$.searchAjax.defaultOptions, options);
            // Put your initialization code here

            // if search btn true
            if (base.options.searchByBtn) {
                base.$el.on('click', function() {
                    base.doSearch();
                });
            } else {
                base.$el.on('keyup', function() {
                    base.doSearch(base.el.value);
                });
            }

            if (base.options.paginate) {
                $(document).on('click', '#' + base.options.paginateId + " a", function(event) {
                    event.preventDefault();
                    let href = $(this).attr('href');
                    base.options.url = href;
                    base.doPaginate(href);
                });
            }
        };

        // Sample Function, Uncomment to use
        // base.functionName = function(paramaters){
        //
        // };

        let currentRequest = null;
        base.doSearch = function (keyword = null) {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(function() {
                data = { keyword: keyword };
                let url = options.url;
                if (options.urlParam) {
                    url = options.url + '/' + keyword;
                }
                currentRequest = $.ajax({
                    url: url,
                    type: options.method,
                    dataType: 'JSON',
                    global: options.global,
                    data: data,
                    async: true,
                    beforeSend : function() {
                        $(options.spinner).show();
                        if(currentRequest != null) {
                            currentRequest.abort();
                        }
                    },
                    success: function(res) {
                        options.onResult(res);
                        $(options.spinner).hide();
                    },
                    error: function(xhr) {
                        options.onError(xhr);
                        $(options.spinner).hide();
                    }
                });
            }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
        };

        base.doPaginate = function (url) {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(function() {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'JSON',
                    async: false,
                    success: function(res) {
                        options.onResult(res);
                    },
                    error: function(xhr) {
                        options.onError(xhr);
                    }
                });
            }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
        };

        // Run initializer
        base.init();
    };

    $.searchAjax.defaultOptions = {
        url: "",
        urlParam: false,
        param: {},
        filter: {},
        searchByBtn: false,
        method: 'get',
        paginate: false,
        global: true,
        paginateId: "",
        onResult: function(data) {},
        onError: function(res) {}
    };

    $.fn.searchAjax = function(options){
        return this.each(function(){
            (new $.searchAjax(this, options));

		   // HAVE YOUR PLUGIN DO STUFF HERE


		   // END DOING STUFF

        });
    };

})(jQuery);
