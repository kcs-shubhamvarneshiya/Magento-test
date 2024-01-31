define(['jquery', 'jquery/ui'], function($) {
    $.widget('custom.sortableTable', {
        options:{
            data:null
        },
        _create: function(config,element,data) {
            // Initialization code here
            this._bindEvents();
        },

        _bindEvents: function() {
            if(this.options.data.length>0)
            {
                $.each(this.options.data, function (element, data) {
                    // Handle click event on table headers
                    $(data.classname).on('click', function() {
                        window.location=data.upsorturl;
                        console.log(data);
                    });

                });
            }
        },

    });

    return $.custom.sortableTable;
});