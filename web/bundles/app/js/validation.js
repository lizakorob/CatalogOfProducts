function loginValidate() {
    var usernameItem = document.getElementById('username').value;
    var passwordItem = document.getElementById('password').value;

    isRegisterUser(usernameItem, passwordItem);
}

function registrationValidate() {
    var nameItem = document.getElementById('register_firstName').value;
    var surnameItem = document.getElementById('register_surname').value;
    var usernameItem = document.getElementById('register_username').value;
    var emailItem = document.getElementById('register_email').value;
    var passwordItem = document.getElementById('register_password_first').value;
    var confirmPasswordItem = document.getElementById('register_password_second').value;

    if (!nameValidate(nameItem)) {
        showMessage('registrationError', 'Имя должно быть длиной от 1 до 15 символов');
        setErrorState('register_firstName');
        return false;
    }
    else if (!surnameValidate(surnameItem)) {
        showMessage('registrationError', 'Фамилия должна быть длиной от 1 до 20 символов');
        setErrorState('register_surname');
        return false;
    }
    else if (!usernameValidate(usernameItem)) {
        showMessage('registrationError', 'Логин должен быть длиной от 3 до 16 символов');
        setErrorState('register_username');
        return false;
    }
    else if(!emailValidate(emailItem)) {
        showMessage('registrationError', 'Поле E-mail заполнено некорректно');
        setErrorState('register_email');
        return false;
    }
    else if (!passwordValidate(passwordItem)) {
        showMessage('registrationError', 'Пароль должен быть длиной от 8 до 64 символов');
        setErrorState('register_password_first');
        return false;
    }
    else if (!checkConfirmPassword(passwordItem, confirmPasswordItem)) {
        showMessage('registrationError', 'Введенные пароли не совпадают');
        setErrorState('register_password_second');
        return false;
    }

    isRegisterData(usernameItem, emailItem);
}

function forgotValidate() {
    var emailItem = document.getElementById('forgot_email').value;

    $.ajax({
        type: 'POST',
        url: '/forgot_password',
        data: {
            'email': emailItem
        },
        success: function (data) {
            if(data['status'] === '200') {
                $("form[name='forgot']").submit();
            } else {
                showMessage('emailForgotError', data['message']);
                setErrorState('forgot_email');
            }
        }
    });
}

function emailValidate(emailItem) {
    const emailPattern = /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i;
    return emailItem.match(emailPattern);
}

function passwordValidate(passwordItem) {
    return !(passwordItem.length < 8 || passwordItem.length > 64);
}

function nameValidate(nameItem) {
    return !(nameItem.length < 1 || nameItem.length > 15);
}

function surnameValidate(surnameItem) {
    return !(surnameItem.length < 1 || surnameItem.length > 20);
}

function usernameValidate(usernameItem) {
    return !(usernameItem.length < 3 || usernameItem.length > 16);
}

function checkConfirmPassword(passwordItem, confirmPasswordItem) {
    return passwordItem === confirmPasswordItem;
}

function showMessage(idFormError, message) {
    document.getElementById(idFormError).innerHTML = message;
}

function setErrorState(idItem) {
    document.getElementById(idItem).classList.add("form-control");
    document.getElementById(idItem).parentNode.classList.add("has-error");
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
            if(data['status'] === '200') {
                $("form[name='login']").submit();
            } else {
                showMessage('loginError', data['message']);
                $('#password').val('');
            }
        }
    });
}

function productValidate(productId) {
    var productName = document.getElementById('edit_product_name').value;
    var productCategory = document.getElementById('edit_product_category').value;
    var productPrice = document.getElementById('edit_product_price').value;
    var productManufacturer = document.getElementById('edit_product_manufacturer').value;
    var productDescription = document.getElementById('edit_product_description').value;
    var productSku = document.getElementById('edit_product_sku').value;

    if (!productNameValidate(productName)) {
        showMessage('productError', 'Название должно быть длиной от 2 до 100 символов');
        return false;
    }

    if (!productCategoryValidate(productCategory)) {
        showMessage('productError', 'Категория должно быть длиной от 2 до 100 символов');
        return false;
    }

    if(!productPriceValidate(productPrice)) {
        showMessage('productError', 'Поле стоимости заполнено некорректно');
        return false;
    }

    if(!productManufacturerValidate(productManufacturer)) {
        showMessage('productError', 'Поле производителя должно быть длиной от 2 до 100 символов');
        return false;
    }

    if(!productDescriptionValidate(productDescription)) {
        showMessage('productError', 'Описание должно быть длиной от 5 до 500 символов');
        return false;
    }

    if (!isExistCategory(productCategory)) {
        showMessage('productError', 'Категория не найдена');
        return false;
    }

    if (!isExistManufacturer(productManufacturer)) {
        showMessage('productError', 'Производитель не найден');
        return false;
    }

    if (productId === null) {
        isExistsProduct(productName, productSku, '/products/create');
    }
    else {
        isExistsProduct(productName, productSku, '/products/edit/' + productId);
    }
}

function filledField(item) {
    var pattern = /^[\s]+$/;
    return !(item.match(pattern));
}

function productNameValidate(productName) {
    return (productName.length > 2 && productName.length < 100 && filledField(productName));
}

function productCategoryValidate(productCategory) {
    return (productCategory.length > 2 && productCategory.length < 100 && filledField(productCategory));
}

function productPriceValidate(productPrice) {
    var pattern = /^\d+$/;
    return (productPrice.match(pattern) && productPrice > 0.01 && productPrice < 100);
}

function productManufacturerValidate(productManufacturer) {
    return (productManufacturer.length > 2 && productManufacturer.length < 100 && filledField(productManufacturer));
}

function productDescriptionValidate(productDescription) {
    return (productDescription.length > 5 && productDescription.length < 500 && filledField(productDescription));
}

function isExistsProduct(name, sku, url) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            'name': name,
            'sku': sku
        },
        success: function (data) {
            if(data['status'] === '200') {
                $("form[name='edit_product']").submit();
            } else {
                showMessage('productError', data['message']);
            }
        }
    });
}