$.ajax({
    type: 'POST',
    url: '/products/get_all_categories',
    success: function (data) {
        data.forEach(function (item, index) {
            if (item.parent === null) {
                $('ul[name="root"]').append('<li id="item' + index + '"><label class="tree-toggler nav-header">' + item.name + '</label>' +
                    '</li><li class="divider"></li>');
                delete data[index];
                setChildItem(data, item, $('ul[name="root"] li#item' + index));
            }
        });

        setEvents();
    }
});

function setChildItem(data, itemEl, element) {
    data.forEach(function (item, index) {
        if (item.parent !== null) {
            if (itemEl.id === item.parent.id) {
                if (isHasChild(data, item)) {
                    element.append('<ul class="nav nav-list tree"><li id="item' + index + '"><label class="tree-toggler nav-header">' + item.name + '</label>');
                    delete data[index];
                    setChildItem(data, item, $('ul[name="root"] li#item' + index));
                } else {
                    element.append('<ul class="nav nav-list tree"><li><a>' + item.name + '</a></li></ul>');
                    delete data[index];
                }
            }
        }
    });
}

function isHasChild(data, itemEl) {
    var flag = false;
    data.some(function (item) {
        if (item.parent !== null && itemEl.id === item.parent.id) {
            flag = true;
        }
        return flag;
    });

    return flag;
}

function setEvents() {
    $(document).ready(function () {
        $('label.tree-toggler').parent().children('ul.tree').toggle(300);
        $('label.tree-toggler').click(function () {
            $(this).parent().children('ul.tree').toggle(300);
            getProducts(this);
        });

        $('ul[name="root"].nav-list a').click(function () {
            getProducts(this);
        });
    });
}

function getProducts(element) {
    var pattern = $(element).html();
    var admin = $('#userRole').val();

    $('h1').html('Категория: "' + pattern + '"');

    $('#grid').ajaxgrid({
        url: '/products',
        sortableColumns: ['name', 'price'],
        filterableColumns: ['name', 'price', 'manufacturer'],
        itemsPerPage: [8, 14, 20],
        page: 1,
        role: admin,
        settings: {
            filter_by_field: 'category',
            pattern: pattern
        },
        view: 'bricks'
    });
    return false;
}