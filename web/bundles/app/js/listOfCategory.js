var categories;
var count = 0;
$("div.list-group").css("position", "absolute");

stylesList($("div.list-group"), $('.categoryField'));

function stylesList(elementToChange, element) {
    var topElement = $(element).offset().top + $(element).outerHeight();
    var left = $(element).offset().left;
    var width = $(element).outerWidth();

    $(elementToChange).offset({top: topElement, left: left});
    $(elementToChange).css("width", width);
}

$('.categoryField').keyup(function () {
    var filter = $(this).val();
    var listCategories = $("div.list-group");
    stylesList(listCategories, this);
    var body = "";

    $.ajax({
        type: 'POST',
        url: '/categories/get_by_filter',
        async: false,
        data: {
            filter: filter,
            category: $('#edit_category_name').val()
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
            }

            if (data.length === 1) {
                $('.category_idField').val(data[0].id);
            }
        }
    });

    setEvents($('div.list-group a'), '#edit_category_parent');

    $(this).blur(function () {
        $(listCategories).hide();
    });
});

function setEvents(elementToHover, element) {
    $(elementToHover).hover(function () {
        if ($(this).text() !== 'Ничего не найдено...') {
            $(element).val($(this).text());

            $('.category_idField').val($(this).attr("id"));
            count = 1;
        }
    });
}

function isExistCategory() {
    var text = $('.category_idField').val().trim(' ');
    return count === 1 || text === '';
}

function setEventForSubmit(url, isEdit) {
    if (isEdit) {
        count = 1;
    }

    $('#saveCategory').click(function () {
        if (!productCategoryValidate($('#edit_category_name').val())) {
            showMessage('categoryError', 'Категория должно быть длиной от 2 до 100 символов');
            return false;
        }
        if(!isExistCategory()) {
            showMessage('categoryError', 'Имя родительской категории не найдено');
            return false;
        }

        var name = $('#edit_category_name').val();

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                name: name
            },
            success: function (data) {
                if(data['status'] === '200') {
                    $("form[name='edit_category']").submit();
                } else {
                    showMessage('categoryError', data['message']);
                }
            }
        });
    });
}