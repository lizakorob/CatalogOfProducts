(function($) {
    $.fn.ajaxgrid = function( options ) {
        grid = $( '#grid' );

        var setup = {
            page: 1,
            sort: null,
            filter: null,
            items : options.itemsPerPage[0]
        };
        options = $.extend( options, setup );
        console.log(options);
        createPanel();
        ajaxMain( options );

        function ajaxMain( options ) {
            let data = {
                product: [
                    {
                        "id":1,
                        "name": "Конфета 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 100,
                        "manufacturer": "factory 12345",
                    },
                    {
                        "id":2,
                        "name": "Зефир 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 1000,
                        "manufacturer": "factory 12345",
                    },
                    {
                        "id":3,
                        "name": "каккуп 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 1060,
                        "manufacturer": "factory 12345",
                    },
                    {
                        "id":4,
                        "name": "кппкпкукпу 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 1200,
                        "manufacturer": "factory 12345",
                    },
                    {
                        "id":5,
                        "name": "Конфета 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 100,
                        "manufacturer": "factory 12345",
                    },
                    {
                        "id":6,
                        "name": "Зефир 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 1000,
                        "manufacturer": "factory 12345",
                    },
                    {
                        "id":7,
                        "name": "каккуп 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 1060,
                        "manufacturer": "factory 12345",
                    },
                    {
                        "id":8,
                        "name": "кппкпкукпу 2123",
                        "description": "fregeregregregregrgrere",
                        "price": 1200,
                        "manufacturer": "factory 12345",
                    }
                ]
            };
            createGrid( data );
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
                options.items = parseInt($(this).text());
                console.log( options );
                clearCatalog();
                ajaxMain( options );
            });
        }

        function createGrid( data ) {
            grid.append( '<div class="catalog"></div>' );
            data.product.forEach( function ( item, i ) {
                createProductItem( item );
            });
            createPagination( data.product.length );
        }

        function createProductItem( item ) {
            $( '.catalog' ).append(
                '<div class="product col-xs-12 col-sm-6 col-md-3" id="prod' + item.id + '">' +
                    '<img src="https://vk.com/images/gifts/256/312.jpg" />' +
                    '<strong>' + item.name + '</strong><br>' +
                    '<span>' + item.price + '</span><br>' +
                '</div>'
            );
            if ( options.admin ) {
                createCRUD ( $( '#prod' + item.id ), item );
            }
        }

        function createCRUD( product, item ) {
            product.prepend(
                '<div class="admin btn-group btn-group-sm" role="group"  style="display: none;">' +
                    '<a href="/edit/' + item.id + '" class="btn btn-default " >' +
                        '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' +
                    '</a>' +
                    '<a href="/delete/' + item.id + '" class="btn btn-default " >' +
                        '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' +
                    '</a>' +
                '</div>'
            )
        }

        function createPagination( length ) {
            grid.append( '<ul class="pagination"></ul>' );
            for ( i = 1; i < ( length / options.items ) + 1; i++ ) {
                $( '.pagination' ).append( '<li><a href="#">' + i + '</a></li>' );
                if ( options.page === i ) {
                    $( '.pagination li' ).addClass( 'active' );
                }
            }
        }

        function clearCatalog() {
            $('.catalog').remove();
            $('.pagination').remove();
        }
    };
})(jQuery);