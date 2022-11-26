$(document).ready(function () {
    var showError = function (message) {
        $('.contact-error-message').html(message).show();
    }

    var showSuccess = function (message) {
        $('.contact-success-message').html(message).show();
    }

    var handleError = function (data) {
        if (typeof (data.errors) !== 'undefined' && data.errors.length) {
            handleValidationError(data.errors);
        } else {
            if (typeof (data.responseJSON) !== 'undefined') {
                if (typeof (data.responseJSON.errors) !== 'undefined') {
                    if (data.status === 422) {
                        handleValidationError(data.responseJSON.errors);
                    }
                } else if (typeof (data.responseJSON.message) !== 'undefined') {
                    showError(data.responseJSON.message);
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            showError(item);
                        });
                    });
                }
            } else {
                showError(data.statusText);
            }
        }
    }

    var handleValidationError = function (errors) {
        let message = '';
        $.each(errors, (index, item) => {
            if (message !== '') {
                message += '<br />';
            }
            message += item;
        });
        showError(message);
    }

    $(".contact-form .nice--input").each(function () {
        var input = $(this).find('input');

        input = input.length > 0 ? input : $(this).find('select');
        input = (input.val() === undefined) ? $(this).find('textarea') : input;

        var self = $(this);

        if(input.val() !== undefined) {
            if(input.val().length > 0 && !$(this).hasClass('filled')) $(this).addClass('filled');
        }
        if(input.attr('type') === 'date') {
            if(!$(this).hasClass('filled')) $(this).addClass('filled');
        }

        input.on('focus', function (e) {
            if(!self.hasClass('filled')) {
                self.addClass('filled')
            }
        });
        input.on('blur', function (e) {
            if($(this).val().length == 0 && input.attr('type') !== 'date') {
                self.removeClass('filled')
            }
        });
        input.on('change', function (e) {
            if($(this).val().length == 0 && self.hasClass('filled')) {
                self.addClass('filled')
            } else if($(this).val().length > 0 && !self.hasClass('filled')) {
                self.removeClass('filled')
            }
        });

    });


    $(document).on('click', '.contact-form button[type=submit]', function (event) {
        event.preventDefault();
        event.stopPropagation();

        $(this).addClass('button-loading');
        $('.contact-success-message').html('').hide();
        $('.contact-error-message').html('').hide();

        $.ajax({
            type: 'POST',
            cache: false,
            url: $(this).closest('form').prop('action'),
            data: new FormData($(this).closest('form')[0]),
            contentType: false,
            processData: false,
            success: res => {
                if (!res.error) {
                    $(this).closest('form').find('input[type=text]').val('');
                    $(this).closest('form').find('input[type=email]').val('');
                    $(this).closest('form').find('textarea').val('');
                    showSuccess(res.message);
                } else {
                    showError(res.message);
                }

                $(this).removeClass('button-loading');

                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
            },
            error: res => {
                if (typeof refreshRecaptcha !== 'undefined') {
                    refreshRecaptcha();
                }
                $(this).removeClass('button-loading');
                handleError(res);
            }
        });
    });
});
