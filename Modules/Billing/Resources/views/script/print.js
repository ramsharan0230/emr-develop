(function ($) {
    $.PrintPlugin = function (options) {
        var defaults = {
            selector: '',
            remotefetch: {
                loadFormRemote : false,
                requestType : null,
                origin : null,
                responseProperty : null,
                payload : {
                },
            },
            message: null,
            redirect : null
        }
        var plugin = this;
        plugin.settings = {};
        plugin.print = function () {
            plugin.settings = $.extend({}, defaults, options);
            if (options.print) options.print.call(plugin)
            if(plugin.settings.selector ){
                return $(plugin.settings.selector).each(function () {
                    var container = $(this);
                    var hidden_IFrame = $('<iframe></iframe>').attr({
                        width: '1px',
                        height: '1px',
                        display: 'none'
                    }).appendTo(container);
                    var myIframe = hidden_IFrame.get(0);
                    var script_tag = myIframe.contentWindow.document.createElement("script");
                    script_tag.type = "text/javascript";
                    script = myIframe.contentWindow.document.createTextNode('function Print(){ window.print(); }');
                    script_tag.appendChild(script);

                    myIframe.contentWindow.document.body.innerHTML = container.html();
                    myIframe.contentWindow.document.body.appendChild(script_tag);

                    myIframe.contentWindow.Print();
                    hidden_IFrame.remove();
                });
            }

            if(plugin.settings.remotefetch.loadFormRemote == true){
                $.ajax({
                    type : plugin.settings.remotefetch.requestType,
                    url : plugin.settings.remotefetch.origin,
                    data : plugin.settings.remotefetch.payload,
                    success : function(response){

                        var hidden_IFrame = $('<iframe></iframe>').attr({
                            width: '1px',
                            height: '1px',
                            display: 'none'
                        })
                        .appendTo('body');
                        var myIframe = hidden_IFrame.get(0);
                        var script_tag = myIframe.contentWindow.document.createElement("script");
                        script_tag.type = "text/javascript";
                        script = myIframe.contentWindow.document.createTextNode('function Print(){ window.print(); }');
                        script_tag.appendChild(script);
                        if(plugin.settings.remotefetch.responseProperty){
                            myIframe.contentWindow.document.body.innerHTML = response[plugin.settings.remotefetch.responseProperty];
                        }else{
                            myIframe.contentWindow.document.body.innerHTML = response;
                        }
                        myIframe.contentWindow.document.body.appendChild(script_tag);
                        myIframe.contentWindow.Print();
                        hidden_IFrame.remove();
                    }
                })
            }
        }
        plugin.print();
    }
}(jQuery));

