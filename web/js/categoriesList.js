var categories;
var manufacturers;
$("div.list-group").css("position", "absolute");

$.ajax({
    type: 'POST',
    url: '/products/get_all_categories',
    success: function (data) {
        categories = data;
        keyUpForInput($('#edit_product_category'), true, categories);
    }
});

$.ajax({
    type: 'POST',
    url: '/products/get_all_manufacturers',
    success: function (data) {
        manufacturers = data;
        keyUpForInput($('#edit_product_manufacturer'), false, manufacturers);
    }
});
function keyUpForInput(element, isCategory, entity) {
    $(element).keyup(function () {
        var listCategories = $("div.list-group");
        stylesList(listCategories, this);
        console.log('aaa');
        var text = $(this).val().toLowerCase();
        var count = 0; var body = "";
        listCategories.html("");

        entity.some(function (item) {
            if (item.name.toLowerCase().indexOf(text) > -1) {
                body += ("<a class='list-group-item' id='" + item.id + "'>" + item.name + "</a>");
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
            }
        }
    });
}