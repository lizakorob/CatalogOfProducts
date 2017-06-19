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
            'price': 'Цена',
            'username': 'Логин'
        };

        var roles = {
            'ROLE_ADMIN':'Администратор',
            'ROLE_MODERATOR': 'Модератор',
            'ROLE_USER': 'Пользователь'
        };

        if (options.settings) {
            setup.settings.filter_by_field = options.settings.filter_by_field;
            setup.settings.pattern = options.settings.pattern;
        }

        options = $.extend( options, setup );
        ajaxMain( options );

        function ajaxMain( options ) {
            grid.html('');
            createPanel();
            $.ajax({
                type: 'GET',
                url: options.url,
                async: false,
                data: setup.settings,
                success: function(data){
                    if (data.length !== 0) {
                        createGrid(data);
                        createModalForm();
                    } else {
                        createWarning();
                    }
                }
            });

            var length = getCountOfElement();
            createPagination( length );
        }

        function createPanel() {
            grid.append( '<div class="panel"></div>' );
            this.panel = $( '.panel' );
            this.panel.addClass( 'panel' );
            createSortableItems( this.panel );
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
                if (setup.settings.order === 'asc') {
                    setup.settings.order = 'desc';
                } else {
                    setup.settings.order = 'asc';
                }
                setup.settings.page = 1;
                ajaxMain( options );
            });
        }

        function createItemsSetting( that ) {
            that.append( '<span class="itemsBlock col-xs-12 col-md-6" name="itemsOnPage">Показывать по:</span>' );
            $.each( options.itemsPerPage, function ( key, value ) {
                $( 'span[name="itemsOnPage"]' ).append( '<button type="button" class="items btn btn-link" id="' + value + '">' + value + '</button>' );
                setEventForItems('span[name="itemsOnPage"] button#' + value, value);
            });
        }

        function setEventForItems(element, items) {
            $( element ).click( function() {
                setup.settings.items = items;
                setup.settings.page = 1;
                ajaxMain( options );
            });
        }

        function createGrid( data ) {
            if ( options.view === 'bricks' ) {
                grid.append( '<div class="catalog"></div>' );
            } else {
                grid.append( '<table class="table table-striped"></table>' );
                createTableHeaders();
            }
            data.forEach( function ( item ) {
                createItem( item, options.view );
            });
        }

        function createTableHeaders() {
            $('.table').append('<thead><tr class="header"></tr></thead>');
            $.each( options.headers, function ( key, value ) {
                $( '.header' ).append( '<th>' + value + '</th>' );
            });
            $('.header').append('<th>Функции</th>');
        }
        
        function getCountOfElement() {
            var length;
            $.ajax({
                type: 'GET',
                url: options.url + '/get_count',
                async: false,
                data: setup.settings,
                success: function(data){
                    length = data;
                }
            });
            return parseInt(length);
        }

        function createItem( item, view ) {
            if ( view === 'bricks' ) {
                createBrickItem(item);
            } else {
                if ( options.url === '/categories' ) {
                    createCategoryTableItem( item );
                } else {
                    createUserTableItem( item );
                }
            }
        }

        function createBrickItem( item ) {
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
            if ( options.role === 'moderator' ) {
                createCRUD ( $( 'div#prod' + item.id ), item );
            }
        }

        function createCategoryTableItem(item) {
            $('.table').append('<tr name="row' + parseInt(item.id) + '">');
            $('tr[name="row' + parseInt(item.id) + '"]').append( '<td>' + item.id + '</td>' );
            $('tr[name="row' + parseInt(item.id) + '"]').append( '<td>' + item.name + '</td>' );
            if (item.parent != null) {
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td>' + item.parent.name + '</td>');
            } else {
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td>' + item.parent + '</td>');
            }
            $('tr[name="row' + parseInt(item.id) + '"]').append( '<td>' + '<a href="edit/' + item.id + '">' +
                '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>' +
                '<a name="del' + item.id + '" data-toggle="modal" data-target="#deleteModal">' +
                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>' +
                '</td>' );
            $('.table').append('</tr>');
            $('tr a[name="del' + item.id + '"]').click(function () {
                $('#elementId').val(item.id);
            });
        }

        function createUserTableItem( item ) {
            if (item.username !== options.username) {
                $('.table').append('<tr name="row' + parseInt(item.id) + '">');
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td>' + item.id + '</td>');
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td>' + item.firstName + " " + item.surname + '</td>');
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td>' + item.username + '</td>');
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td>' + item.email + '</td>');
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td><select name="sel' + item.id + '"><option>Пользователь</option>' +
                    '<option>Модератор</option><option>Администратор</option></select></td>');
                $('tr[name="row' + parseInt(item.id) + '"]').append('<td><a>' +
                    '<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></a>' +
                    '<a name="del' + item.id + '" data-toggle="modal" data-target="#deleteModal">' +
                    '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>' +
                    '</td>');
                setSelectedUserRole(item);
                $('.table').append('</tr>');
                $('tr[name="row' + parseInt(item.id) + '"] a').click(function () {
                    var el = $('select[name="sel' + item.id + '"]').val();
                    var roleUser = 'ROLE_USER';
                    $.each(roles, function (index, item) {
                        if (el === item) {
                            roleUser = index;
                        }
                    });

                    if (el !== roles[item.role]) {
                        $.ajax({
                            type: 'POST',
                            url: '/users/set_role',
                            data: {
                                userId: item.id,
                                role: roleUser
                            },
                            success: function (data) {
                                $('#messageInfo').html(data);
                                $('.alert').css("display", 'block');
                                ajaxMain(options);

                                setTimeout(function () {
                                    $('.alert').css("display", 'none');
                                }, 2000)
                            }
                        });
                    }
                });
                $('tr a[name="del' + item.id + '"]').click(function () {
                    $('#elementId').val(item.id);
                });
            }
        }

        function setSelectedUserRole(item) {
            $('select[name="sel' + item.id + '"]').val(roles[item.role]);
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
                $('#elementId').val(item.id);
            });
        }

        function createPagination(length) {
            grid.append('<ul class="pagination col-xs-12" name="pagination"></ul>');
            for ( var i = 1; i <= ( length / options.settings.items ) + 1; i++ ) {
                $('ul[name="pagination"].pagination').append( '<li name="page' + i + '" id="' + i + '"><a>' + i + '</a></li>' );
                if ( setup.settings.page === i ) {
                    $('ul[name="pagination"].pagination li[name="page' + i + '"]').addClass('active');
                } else {
                    setEventForPage($('ul[name="pagination"].pagination li[name="page' + i + '"]'));
                }
            }
        }

        function setEventForPage(element) {
            $(element).click(function () {
                setup.settings.page = parseInt(this.id);
                ajaxMain(options);
            });
        }

        function createModalForm() {
            grid.append('<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
                '<div class="modal-dialog"><div class="modal-content"><div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                '<h4 class="modal-title" id="myModalLabel">Вы действительно хотите удалить?</h4></div><div class="modal-body">' +
                '<form class="delete" method="post">' +
                '<div class="col-sm-offset-2 col-sm-8"><div class="error"><span id="registrationError"></span></div>' +
                '<input type="hidden" id="elementId">' +
                '<input type="button" id="yes" value="Да" class="btn btn-default" />' +
                '<input type="button" data-dismiss="modal" value="Нет" class="btn btn-default" />' +
                '</div></form></div></div></div></div>');

            $('#yes').click(function () {
                var elementId = $('#elementId').val();
                $.ajax({
                    type: 'POST',
                    url: options.url + '/delete/' + elementId,
                    success: function () {
                        window.location.replace(options.url);
                    }
                });
            });
        }

        function createWarning() {
            grid.append('<div class="alert alert-danger">' +
                'По вашему запросу ничего не найдено</div>');
        }
    };
})(jQuery);