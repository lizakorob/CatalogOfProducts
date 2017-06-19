function editUser(id) {
    if (!nameValidate($('#edit_user_firstName').val())) {
        showMessage('editError', 'Имя должно быть длиной от 1 до 15 символов');
        setErrorState('edit_user_firstName');
        return false;
    }
    else if (!surnameValidate($('#edit_user_surname').val())) {
        showMessage('editError', 'Фамилия должна быть длиной от 1 до 20 символов');
        setErrorState('edit_user_surname');
        return false;
    }
    else if (!usernameValidate($('#edit_user_username').val())) {
        showMessage('editError', 'Логин должен быть длиной от 3 до 16 символов');
        setErrorState('edit_user_username');
        return false;
    }

    if (!emailValidate($('#edit_user_email').val())) {
        showMessage('editError', 'Поле E-mail заполнено некорректно');
        setErrorState('edit_user_email');
        return false;
    }

    isRegisterData($('#edit_user_username').val(), $('#edit_user_email').val(), id);
}

function isRegisterData($username, $email, $id) {
    $.ajax({
        type: 'POST',
        url: '/users/edit/' + $id,
        data: {
            'username': $username,
            'email': $email
        },
        success: function (data) {
            if(data['status'] === '200') {
                $("form[name='edit_user']").submit();
            } else {
                showMessage('editError', data['message']);
            }
        }
    });
}