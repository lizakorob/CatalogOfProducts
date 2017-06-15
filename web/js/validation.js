function loginValidate() {
    var usernameItem = document.getElementById('username').value;
    var passwordItem = document.getElementById('password').value;
    isRegisterUser(usernameItem, passwordItem);
}

function registrationValidate() {
    let nameItem = document.getElementById('user_registration_firstName').value;
    let surnameItem = document.getElementById('user_registration_surname').value;
    let usernameItem = document.getElementById('user_registration_username').value;
    let emailItem = document.getElementById('user_registration_email').value;
    let passwordItem = document.getElementById('user_registration_password_first').value;
    let confirmPasswordItem = document.getElementById('user_registration_password_second').value;

    if (!nameValidate(nameItem)) {
        showMessage('registrationError', 'Имя должно быть длиной от 1 до 15 символов');
        setErrorState('user_registration_firstName');
        return false;
    }
    else if (!surnameValidate(surnameItem)) {
        showMessage('registrationError', 'Фамилия должна быть длиной от 1 до 20 символов');
        setErrorState('user_registration_surname');
        return false;
    }
    else if (!usernameValidate(usernameItem)) {
        showMessage('registrationError', 'Логин должен быть длиной от 3 до 16 символов');
        setErrorState('user_registration_username');
        return false;
    }
    else if(!emailValidate(emailItem)) {
        showMessage('registrationError', 'Поле E-mail заполнено некорректно');
        setErrorState('user_registration_email');
        return false;
    }
    else if (!passwordValidate(passwordItem)) {
        showMessage('registrationError', 'Пароль должен быть длиной от 8 до 64 символов');
        setErrorState('user_registration_password_first');
        return false;
    }
    else if (!checkConfirmPassword(passwordItem, confirmPasswordItem)) {
        showMessage('registrationError', 'Введенные пароли не совпадают');
        setErrorState('user_registration_password_second');
        return false;
    }
    isRegisterData(usernameItem, emailItem);
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
            if (data['status'] === '200') {
                $("form[name='login']").submit();
            } else {
                showMessage('loginError', data['message']);
                $('#password').val('');
            }
        }
    });
}
