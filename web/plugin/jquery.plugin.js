(function($) {
    $.fn.ajaxgrid = function(options){
        ajaxMain(options);
        function createPanel() {
            this.append('<div class="panel"></div>');
            return this;
        }

        function ajaxMain(options) {
            // $.ajax({
            //     url: '/',
            //     success: function(data){
            //         setTable();
            //         setHeaders(data[0]);
            //         setBody(data);
            //         setSortable();
            //         setButtons();
            //         setButtonsWorkable();
            //     },
            // });

            createPanel();
        }
        //return this;
    };
})(jQuery);