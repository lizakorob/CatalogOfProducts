var categories;
var manufacturers;
$("div.list-group").css("position", "absolute");

$.ajax({
    type: 'POST',
    url: '/products/get_all_categories',
    success: function (data) {
        categories = data;
        keyUpForInput($('.categoryField'), true, categories);
    }
});

$.ajax({
    type: 'POST',
    url: '/products/get_all_manufacturers',
    success: function (data) {
        manufacturers = data;
        keyUpForInput($('.manufacturerField'), false, manufacturers);
    }
});

stylesList($("div.list-group"), $('.categoryField'));

function keyUpForInput(element, isCategory, entity) {
    $(element).keyup(function () {
        var listCategories = $("div.list-group");
        stylesList(listCategories, this);

        var text = $(this).val().toLowerCase();
        var count = 0; var body = ""; var flag;
        listCategories.html("");

        entity.some(function (item) {
            if (!isCategory){
                flag = (item.name + ", " + item.country).toLowerCase().indexOf(text)
            } else {
                flag = item.name.toLowerCase().indexOf(text);
            }

            if (flag > -1) {
                if (isCategory) {
                    body += ("<a class='list-group-item' id='" + item.id + "'>" + item.name + "</a>");
                } else {
                    body += ("<a class='list-group-item' id='" + item.id + "'>" + (item.name + ", " + item.country) + "</a>");
                }
                count++;
            }

            return count > 3;
        });

        if (count === 0)
            body += "<a class='list-group-item'><em>Ничего не найдено...</em></a>";

        listCategories.html(body);
        listCategories.show();
        setEvents($('div.list-group a'), this, isCategory);
    });

    $(element).blur(function () {
        $("div.list-group").hide();
    });
}

function stylesList(elementToChange, element) {
    var topElement = $(element).offset().top + $(element).outerHeight();
    var left = $(element).offset().left;
    var width = $(element).outerWidth();

    $(elementToChange).offset({top: topElement, left: left});
    $(elementToChange).css("width", width);
}

function setEvents(elementToHover, element, isCategory) {
    $(elementToHover).hover(function () {
        if ($(this).text() !== 'Ничего не найдено...') {
            $(element).val($(this).text());

            if (isCategory) {
                $('.category_idField').val($(this).attr("id"));
            } else {
                $('#edit_product_manufacturer_id').val($(this).attr("id"));
            }
        }
    });
}

function isExistCategory(category) {
    var flag = false; var index = 0;
    categories.some(function (item) {
        flag = item.name.toLowerCase() === category.toLowerCase();
        index = item.id;
        return flag;
    });

    if (flag) {
        $('.category_idField').val(index);
    }

    return flag;
}

function isExistManufacturer(manufacturer) {
    var flag = false; var index = 0;
    manufacturers.some(function (item) {
        flag = (item.name + ", " + item.country).toLowerCase() === manufacturer.toLowerCase();
        index = item.id;
        return flag;
    });

    if (flag) {
        $('#edit_product_manufacturer_id').val(index);
    }

    return flag;
}