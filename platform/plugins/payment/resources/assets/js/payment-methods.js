'use strict';

class PaymentMethodManagement {
    init() {
        $('.toggle-payment-item').off('click').on('click', event => {
            $(event.currentTarget).closest('tbody').find('.payment-content-item').toggleClass('hidden');
        });
        $('.disable-payment-item').off('click').on('click', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            $('#confirm-disable-payment-method-modal').modal('show');
            $('#confirm-disable-payment-method-button').on('click', event => {
                event.preventDefault();
                $(event.currentTarget).addClass('button-loading');
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: route('payments.methods.update.status'),
                    data: {
                        type: _self.closest('form').find('.payment_type').val()
                    },
                    success: res => {
                        if (!res.error) {
                            _self.closest('tbody').find('.payment-name-label-group').addClass('hidden');
                            _self.closest('tbody').find('.edit-payment-item-btn-trigger').addClass('hidden');
                            _self.closest('tbody').find('.save-payment-item-btn-trigger').removeClass('hidden');
                            _self.closest('tbody').find('.btn-text-trigger-update').addClass('hidden');
                            _self.closest('tbody').find('.btn-text-trigger-save').removeClass('hidden');
                            _self.addClass('hidden');
                            $(event.currentTarget).closest('.modal').modal('hide');
                            Canopy.showSuccess(res.message);
                        } else {
                            Canopy.showError(res.message);
                        }
                        $(event.currentTarget).removeClass('button-loading');
                    },
                    error: res => {
                        Canopy.handleError(res);
                        $(event.currentTarget).removeClass('button-loading');
                    }
                });
            });
        });

        $('.save-payment-item').off('click').on('click', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');
            let payload = _self.closest('form').serializeArray();
            _self.closest('form').find('input[type=checkbox]').each(function (id, item) {
                let itemValue = $(this).is(":checked") ? 1 : 0;
                let itemName = $(this).attr('name');
                let resultIndex = null;

                payload.forEach(function (data, index) {
                    if (data.name == itemName) {
                        resultIndex = index;
                    }
                });

                if (resultIndex != null) {
                    // FOUND replace previous value
                    payload[resultIndex].value = itemValue;
                } else {
                    // NO value has been found add new one
                    payload.push({name: itemName, value: itemValue});
                }
            });
            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: payload,
                success: res => {
                    if (!res.error) {
                        _self.closest('tbody').find('.payment-name-label-group').removeClass('hidden');
                        _self.closest('tbody').find('.method-name-label').text(_self.closest('form').find('input.input-name').val());
                        _self.closest('tbody').find('.disable-payment-item').removeClass('hidden');
                        _self.closest('tbody').find('.edit-payment-item-btn-trigger').removeClass('hidden');
                        _self.closest('tbody').find('.save-payment-item-btn-trigger').addClass('hidden');
                        _self.closest('tbody').find('.btn-text-trigger-update').removeClass('hidden');
                        _self.closest('tbody').find('.btn-text-trigger-save').addClass('hidden');
                        Canopy.showSuccess(res.message);
                    } else {
                        Canopy.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Canopy.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $('.button-save-payment-settings').off('click').on('click', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');
            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: _self.closest('form').serialize(),
                success: res => {
                    if (!res.error) {
                        Canopy.showSuccess(res.message);
                    } else {
                        Canopy.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Canopy.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });
    }
}

$(document).ready(() => {
    new PaymentMethodManagement().init();
});
