(function($) {
    $.fn.ajaxgrid = function( options ) {
        grid = $( '#grid' );
        var setup = {
            url: options.url,
            settings: {
                page: 1,
                items: options.itemsPerPage[0],
                sort_by_field: 'id',
                order: 'asc',
                filter_by_field: null,
                pattern: null
            }
        };

        var sort_by = {
            'name': 'Название',
            'price': 'Цена'
        };

        if (options.settings) {
            setup.settings.filter_by_field = options.settings.filter_by_field;
            setup.settings.pattern = options.settings.pattern;
        }

        createPanel();
        options = $.extend( options, setup );
        ajaxMain( options );

        function ajaxMain( options ) {
            clearGrid();
            $.ajax({
                type: 'GET',
                url: options.url,
                data: setup.settings,
                success: function(data){
                    createGrid( data );
                    createModalForm();
                }
            });
        }

        function createPanel() {
            grid.append( '<div class="panel"></div>' );
            this.panel = $( '.panel' );
            this.panel.addClass( 'panel' );
            createSortableItems( this.panel );
            //createFilterFields( this.panel );
            createItemsSetting( this.panel );
            return this;
        }

        function createSortableItems( that ) {
            that.append( '<span class="sortBlock col-xs-12 col-md-6">Сортировать по:</span>' );
            $.each( options.sortableColumns, function ( key, value ) {
                $( '.sortBlock' ).append('<button type="button" class="sort btn btn-link" id="sortby' + value +
                    '">' + sort_by[value] + '</button>');
                setEventForSort($('.sortBlock button#sortby' + value), value);
            });
        }

        function setEventForSort(element, value) {
            $( element ).click( function() {
                setup.settings.sort_by_field = value;
                ajaxMain( options );
            });
        }

        /*function createFilterFields( that ) {
            // that.append('<span class="sort">Сортировать по:</span>');
            // $.each(options.filterableColumns, function (key, value) {
            //     $('.sort').append("<a href='#'>" + value + ' ' + "</a>");
            // });
        }*/

        function createItemsSetting( that ) {
            that.append( '<span class="itemsBlock col-xs-12 col-md-6">Показывать по:</span>' );
            $.each( options.itemsPerPage, function ( key, value ) {
                $( '.itemsBlock' ).append( '<button type="button" class="items btn btn-link" id=itemsper"' + value + '">' + value + '</button>' );
                setEventForItems($('.itemsBlock button#itemsper' + value), value);
            });
        }

        function setEventForItems(element, items) {
            $( element ).click( function() {
                setup.settings.items = items;
                ajaxMain( options );
            });
        }

        function createGrid( data ) {
            grid.append( '<div class="catalog"></div>' );
            data.forEach( function ( item ) {
                createProductItem( item );
            });
            createPagination( data.length );
        }

        function createProductItem( item ) {
            if (item.image !== null) {
                $('.catalog').append(
                    '<div class="product col-xs-12 col-sm-6 col-md-3" id="prod' + item.id + '">' +
                    '<img src="../uploads/images/' + item.image + '" />' +
                    '<strong><a href="details/' + item.id + '">' + item.name + '</a></strong><br>' +
                    '<span>' + item.price + ' р.</span><br>' +
                    '</div>'
                );
            } else {
                $('.catalog').append(
                    '<div class="product col-xs-12 col-sm-6 col-md-3" id="prod' + item.id + '">' +
                    '<img src="../uploads/noimage.jpg" />' +
                    '<strong><a href="details/' + item.id + '">' + item.name + '</a></strong><br>' +
                    '<span>' + item.price + ' р.</span><br>' +
                    '</div>'
                );
            }
            if ( options.admin ) {
                createCRUD ( $( '#prod' + item.id ), item );
            }
        }

        function createCRUD( product, item ) {
            product.prepend(
                '<div class="admin btn-group btn-group-sm" role="group"  style="display: none;">' +
                    '<a href="edit/' + item.id + '" class="btn btn-default " >' +
                        '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' +
                    '</a>' +
                    '<a class="btn btn-default" id="del' + item.id + '" data-toggle="modal" data-target="#deleteModal">' +
                        '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' +
                    '</a>' +
                '</div>'
            );
            $('div.admin a#del' + item.id).click(function () {
                $('#productId').val(item.id);
            });
        }

        function createPagination( length ) {
            length = 23; //
            grid.append( '<ul class="pagination col-xs-12"></ul>' );
            for ( var i = 1; i <= ( length / options.settings.items ) + 1; i++ ) {
                $( '.pagination' ).append( '<li id="' + i + '"><a>' + i + '</a></li>' );
                if ( setup.settings.page === i ) {
                    $( '.pagination li#' + i ).addClass( 'active' );
                } else {
                    setEventForPage($('.pagination li#' + i), i);
                }
            }
        }

        function setEventForPage(element, page) {
            $(element).click(function () {
                setup.settings.page = page;
                ajaxMain(options);
            });
        }

        function createModalForm() {
            grid.append('<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
                '<div class="modal-dialog"><div class="modal-content"><div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                '<h4 class="modal-title" id="myModalLabel">Вы действительно хотите удалить продукт?</h4></div><div class="modal-body">' +
                '<form class="delete" action="/products/delete" method="post">' +
                '<div class="col-sm-offset-2 col-sm-8"><div class="error"><span id="registrationError"></span></div>' +
                '<input type="hidden" id="productId">' +
                '<input type="button" id="yes" value="Да" class="btn btn-default" />' +
                '<input type="button" data-dismiss="modal" value="Нет" class="btn btn-default" />' +
                '</div></form></div></div></div></div>');

            $('#yes').click(function () {
                var productId = $('#productId').val();
                $.ajax({
                    type: 'POST',
                    url: '/products/delete/' + productId,
                    success: function () {
                        window.location.replace("/products");
                    }
                });
            });
        }

        function clearGrid() {
            $('.catalog').remove();
            $('.pagination').remove();
        }
    };
})(jQuery);