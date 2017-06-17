(function($) {
    $.fn.ajaxgrid = function( options ) {
        grid = $( '#grid' );
        var setup = {
            page: 1,
            sort: null,
            sortDirection: null,
            filter: null,
            itemsPerPage : options.itemsPerPage[0]
        };

        createPanel();
        options = $.extend( options, setup );
        ajaxMain( options );

        function ajaxMain( options ) {
            $.ajax({
                type: 'GET',
                url: options.url,
                data: {
                    'page': options.page
                },
                success: function(data){
                    createGrid( data );
                }
            });
        }

        function createPanel() {
            grid.append( '<div class="panel"></div>' );
            this.panel = $( '.panel' );
            this.panel.addClass( 'panel' );
            createSortableItems( this.panel );
            createFilterFields( this.panel );
            createPageSetting( this.panel );
            return this;
        }

        function createSortableItems( that ) {
            that.append( '<span class="sortBlock col-xs-12 col-md-6">Сортировать по:</span>' );
            $.each( options.sortableColumns, function ( key, value ) {
                $( '.sortBlock' ).append('<button type="button" class="sort btn btn-link" id="sortby' + value +
                    '">' + value + '</button>');
            });
            $( '.sortBlock button' ).click( function() {
                options.sort = this.id.substring( 6 );
                if (options.sortDirection === 'desc') {
                    options.sortDirection = 'asc';
                } else {
                    options.sortDirection = 'desc';
                }
                clearCatalog();
                ajaxMain( options );
            });
        }

        function createFilterFields( that ) {
            // that.append('<span class="sort">Сортировать по:</span>');
            // $.each(options.filterableColumns, function (key, value) {
            //     $('.sort').append("<a href='#'>" + value + ' ' + "</a>");
            // });
        }

        function createPageSetting( that ) {
            that.append( '<span class="pagesBlock col-xs-12 col-md-6">Показывать по:</span>' );
            $.each( options.itemsPerPage, function ( key, value ) {
                $( '.pagesBlock' ).append( '<button type="button" class="pages btn btn-link">' + value + '</button>' );
            });
            $( '.pagesBlock button' ).click( function() {
                options.itemsPerPage = parseInt($(this).text());
                console.log( options );
                clearCatalog();
                ajaxMain( options );
            });
        }

        function createGrid( data ) {
            grid.append( '<div class="catalog"></div>' );
            data.forEach( function ( item, i ) {
                createProductItem( item );
            });
            createPagination( data.length );
        }

        function createProductItem( item ) {
            $( '.catalog' ).append(
                '<div class="product col-xs-12 col-sm-6 col-md-3" id="prod' + item.id + '">' +
                    '<img src="../uploads/images/' + item.image + '" />' +
                    '<strong><a href="details/' + item.id + '">' + item.name + '</a></strong><br>' +
                    '<span>' + item.price + ' р.</span><br>' +
                '</div>'
            );
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
                    '<a href="delete/' + item.id + '" class="btn btn-default">' +
                        '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' +
                    '</a>' +
                '</div>'
            )
        }

        function createPagination( length ) {
            grid.append( '<ul class="pagination"></ul>' );
            for ( i = 1; i < ( length / options.itemsPerPage ) + 1; i++ ) {
                $( '.pagination' ).append( '<li><a href="#">' + i + '</a></li>' );
                if ( options.page === i ) {
                    $( '.pagination li' ).addClass( 'active' );
                }
            }
        }

        function clearCatalog() {
            $( '.catalog' ).remove();
            $( '.pagination' ).remove();
        }

        function createURL() {
            url = options.url;
            query = '';
            query += checkOptions( options.itemsPerPage, 'items' );
            query += checkOptions( options.page, 'page' );
            query += checkOptions( options.sort, 'sortbyfield' );
            query += checkOptions( options.filter, 'filterbyfield' );
            query += checkOptions( options.sortDirection, 'order' );
            if (query.length === 0 ) {
                return url;
            } else {
                return url + '?' + query.substring(1)
            }
        }

        function checkOptions(option, call) {
            console.log(option);
            if ( option !== null ) {
               return '&' + call + '=' + option;
            } else {
                return '';
            }
        }
    };
})(jQuery);