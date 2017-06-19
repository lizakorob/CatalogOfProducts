var products;
$("div[name='searchList']").css("position", "absolute");

stylesListFor($("div[name='searchList']"), $('#searchInput'));

function stylesListFor(elementToChange, element) {
    var topElement = $(element).offset().top + $(element).outerHeight();
    var left = $(element).offset().left;
    var width = $(element).outerWidth();

    $(elementToChange).offset({top: topElement, left: left});
    $(elementToChange).css("width", width);
}

$('#searchInput').keyup(function () {
    var filter = $(this).val();
    var listCategories = $("div[name='searchList']");
    stylesListFor(listCategories, this);
    var body = "";

    $.ajax({
        type: 'POST',
        url: '/products/get_by_filter',
        data: {
            filter: filter
        },
        success: function (data) {
            count = data.length;
            if (data.length === 0) {
                listCategories.html("<a class='list-group-item'><em>Ничего не найдено...</em></a>");
            } else {
                data.forEach(function (item) {
                    body += ("<a class='list-group-item' id='" + item.id + "'>" + item.name + "</a>");
                });
                listCategories.html(body);
                listCategories.show();
                setEventsFor($('div[name="searchList"] a'), '#searchInput');
            }
        }
    });

    $(this).blur(function () {
        $(listCategories).hide();
    });
});

function setEventsFor(elementToHover, element) {
    $(elementToHover).hover(function () {
        if ($(this).text() !== 'Ничего не найдено...') {
            $(element).val($(this).text());
        }
    });
}