$(document).ready(function() {
    function makeFieldValidation( parentId ) {
        $( parentId ).each( function() {
            $( this ).find( 'div' ).addClass( 'form-group' );
        });
    }
    $('.login').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            _username: {
                validators: {
                    notEmpty: {
                        message: 'Поле логина обязательно для заполнения'
                    },
                    stringLength: {
                        min: 3,
                        max: 16,
                        message: 'Длина логина должна быть от 3 до 16 символов'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/,
                        message: 'Логин может содержать только буквы латинского алфавита, ' +
                        'числа, знак нижнего подчеркивания, точку'
                    }
                }
            },
            _password: {
                validators: {
                    notEmpty: {
                        message: 'Поле пароля обязательно для заполнения'
                    },
                    stringLength: {
                        min: 8,
                        max: 64,
                        message: 'Длина пароля должна быть от 8 до 64 символов'
                    }
                }
            }
        }
    });
    makeFieldValidation('#user_registration');
    $('.register').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'user_registration[firstName]': {
                validators: {
                    notEmpty: {
                        message: 'Поле имени обязательно для заполнения'
                    },
                    stringLength: {
                        min: 2,
                        max: 20,
                        message: 'Длина имени должна быть от 2 до 20 символов'
                    },
                    regexp: {
                        regexp: /^[A-zА-яё]+$/i,
                        message: 'Имя может содержать только буквы'
                    }
                }
            },
            'user_registration[surname]': {
                validators: {
                    notEmpty: {
                        message: 'Поле фамилии обязательно для заполнения'
                    },
                    stringLength: {
                        min: 2,
                        max: 20,
                        message: 'Длина фамилии должна быть от 2 до 20 символов'
                    },
                    regexp: {
                        regexp: /^[A-zА-яё]+$/i,
                        message: 'Фамилия может содержать только буквы'
                    }
                }
            },
            'user_registration[username]': {
                validators: {
                    notEmpty: {
                        message: 'Поле логина обязательно для заполнения'
                    },
                    stringLength: {
                        min: 3,
                        max: 16,
                        message: 'Длина логина должна быть от 3 до 16 символов'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/,
                        message: 'Логин может содержать только буквы латинского алфавита, ' +
                        'числа, знак нижнего подчеркивания, точку'
                    }
                }
            },
            'user_registration[email]': {
                validators: {
                    notEmpty: {
                        message: 'Поле электронной почты обязательно для заполнения'
                    },
                    regexp: {
                        regexp: /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i,
                        message: 'Адрес электронной почты заполнен некорректно'
                    }
                }
            },
            'user_registration[password][first]': {
                validators: {
                    notEmpty: {
                        message: 'Поле пароля обязательно для заполнения'
                    },
                    stringLength: {
                        min: 8,
                        max: 64,
                        message: 'Длина пароля должна быть от 8 до 64 символов'
                    }
                }
            },
            'user_registration[password][second]': {
                validators: {
                    identical: {
                        field: 'user_registration[password][first]',
                        message: 'Пароли не совпадают'
                    }
                }
            }
        }
    });
    makeFieldValidation('#forgot_password');
    $('.forgotPassword').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'forgot_password[email]': {
                validators: {
                    notEmpty: {
                        message: 'Поле электронной почты обязательно для заполнения'
                    },
                    regexp: {
                        regexp: /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i,
                        message: 'Адрес электронной почты заполнен некорректно'
                    }
                }
            }
        }
    });
    makeFieldValidation('#edit_category');
    $('.category').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            'edit_category[name]' : {
                validators: {
                    notEmpty: {
                        message: 'Поле названия категории обязательно для заполнения'
                    },
                    regexp: {
                        regexp: /^[а-яА-ЯёЁa-zA-Z "0-9]+$/,
                        message: 'Название категории может содержать только буквы, цифры и кавычки'
                    }
                }
            },
            'edit_category[parent]' : {
                validators: {
                    notEmpty: {
                        message: 'Поле названия категории обязательно для заполнения'
                    },
                    regexp: {
                        regexp: /^[а-яА-ЯёЁa-zA-Z "0-9]+$/,
                        message: 'Название категории может содержать только буквы, цифры и кавычки'
                    }
                }
            }
        }
    });
});