function loginValidate() {
    let usernameItem = document.getElementById('username').value;
    let passwordItem = document.getElementById('password').value;
    isRegisterUser(usernameItem, passwordItem);
    //return false;
}

function registrationValidate() {
    let usernameItem = document.getElementById('user_registration_username').value;
    let emailItem = document.getElementById('user_registration_email').value;
    isRegisterData(usernameItem, emailItem);
    //return false;
}

function showMessage(idFormError, message) {
    document.getElementById(idFormError).innerHTML = message;
}

function isRegisterData($username, $email) {
    $.ajax({
        type: 'POST',
        url: '/register',
        data: {
            'username': $username,
            'email': $email
        },
        success: function (data) {
            if(data['status'] === '200') {
                var password = $('#register_password_first').val();
                $("form[name='register']").submit();
                $('#username').val($username);
                $('#password').val(password);
                $("form[name='login']").submit();
            } else {
                showMessage('registrationError', data['message']);
            }
        }
    });
}

function isRegisterUser($username, $password) {
    $.ajax({
        type: 'POST',
        url: '/sign',
        data: {
            'username': $username,
            'password': $password
        },
        success: function (data) {
            if (data['status'] === '200') {
                $("form[name='login']").submit();
            } else {
                showMessage('loginError', data['message']);
                $('#password').val('');
            }
        }
    });
}

function productValidate() {
    let productName = document.getElementById('edit_product_name').value;
    console.log(productName);
    console
        .log(productName.length);
    let productCategory = document.getElementById('edit_product_category').value;
    let productPrice = document.getElementById('edit_product_price').value;
    let productManufacturer = document.getElementById('edit_product_manufacturer').value;
    let productDescription = document.getElementById('edit_product_description').value;
    // let productIsActive = document.getElementById('edit_product_isActive').value;
    if (!productNameValidate(productName)) {
        showMessage('productError', 'Название должно быть длиной от 2 до 150 символов');
        setErrorState('edit_product_name');
        console.log('имя');
        return false;
    } else if (!productCategoryValidate(productCategory)) {
        showMessage('productError', 'Поле категории заполнено некорректно');
        setErrorState('edit_product_category');
        return false;
    } else if(!productPriceValidate(productPrice)) {
        showMessage('productError', 'Поле стоимости заполнено некорректно');
        setErrorState('edit_product_price');
        return false;
    } else if(!productManufacturerValidate(productManufacturer)) {
        showMessage('productError', 'Поле производителя заполнено некорректно');
        setErrorState('edit_product_manufacturer');
        return false;
    } else if(!productDescriptionValidate(productDescription)) {
        showMessage('productError', 'Описание должно быть длиной от 5 до 500 символов');
        setErrorState('edit_product_description');
        return false;
    }
}

function filledField(item) {
    var pattern = /^[\s]+$/;
    return !(item.match(pattern));
}

function productNameValidate(productName) {
    return (productName.length > 2 && productName.length < 150 && filledField(productName));
}

function productCategoryValidate(productCategory) {
    return (productCategory.length > 2 && productCategory.length < 150 && filledField(productCategory));
}

function productPriceValidate(productPrice) {
    let pattern = /^\d+$/;
    return (productPrice.match(pattern) && productPrice > 0 && productPrice < 10000);
}

function productManufacturerValidate(productManufacturer) {
    return (productManufacturer.length > 2 && productManufacturer.length < 50 && filledField(productManufacturer));
}

function productDescriptionValidate(productDescription) {
    return (productDescription.length > 5 && productDescription.length < 500 && filledField(productDescription));
}

