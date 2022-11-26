class OrderAdminManagement {
    init() {
        $(document).on('click', '.btn-confirm-order', event => {
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
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        _self.closest('div').remove();
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

       /* The above code is using FilePond to upload a video file to the server. */
        const fileInputElement = document.querySelector('input[id="order_video"]');
        const videoUploadUrl = document.querySelector('input[id="video_upload_url"]').value;
        FilePond.create(fileInputElement);
        FilePond.parse(document.body);
        FilePond.setOptions({
            server: {
                process: {
                    url: videoUploadUrl,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    method: 'POST',
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    onload: function(response) {
                        Canopy.showSuccess(response.message);
                    },
                    onerror: function(response) {
                        Canopy.showSuccess(response.message);
                    },
                },
            }
        });
        $(document).on('click', '.choose_file_btn button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            // console.log(_self.closest('form').prop('action'));
            // console.log(_self.closest('form').serialize());
            _self.addClass('button-loading');
            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: _self.closest('form').serialize(),
                success: res => {
                    console.log(res);
                    // if (!res.error) {
                    //     $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                    //     _self.closest('div').remove();
                    //     Canopy.showSuccess(res.message);
                    // } else {
                    //     Canopy.showError(res.message);
                    // }
                    _self.removeClass('button-loading');
                },
                error: res => {
                    Canopy.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });


        $(document).on('click', '.btn-trigger-complete', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');
            _self.removeClass('hidden');
            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('target'),
                success: res => {
                    if (!res.error) {
                        /* $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        _self.closest('div').remove(); */
                        Canopy.showSuccess(res.message);
                        _self.addClass('hidden')
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


        $(document).on('click', '.btn-trigger-resend-order-confirmation-modal', event => {
            event.preventDefault();
            $('#confirm-resend-confirmation-email-button').data('action', $(event.currentTarget).data('action'));
            $('#resend-order-confirmation-email-modal').modal('show');
        });

        $(document).on('click', '#confirm-resend-confirmation-email-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('action'),
                success: res => {
                    if (!res.error) {
                        Canopy.showSuccess(res.message);
                    } else {
                        Canopy.showError(res.message);
                    }
                    _self.removeClass('button-loading');
                    $('#resend-order-confirmation-email-modal').modal('hide');
                },
                error: res => {
                    Canopy.handleError(res);
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.btn-trigger-shipment', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            let $form_body = $('.shipment-create-wrap');
            $form_body.toggleClass('hidden');
            if (!$form_body.hasClass('shipment-data-loaded')) {

                Canopy.blockUI({
                    target: $form_body,
                    iconOnly: true,
                    overlayColor: 'none'
                });

                $.ajax({
                    url: _self.data('target'),
                    type: 'GET',
                    success: res => {
                        if (res.error) {
                            Canopy.showError(res.message);
                        } else {
                            $form_body.html(res.data);
                            $form_body.addClass('shipment-data-loaded');
                            Canopy.initResources();
                        }
                        Canopy.unblockUI($form_body);
                    },
                    error: data => {
                        Canopy.handleError(data);
                        Canopy.unblockUI($form_body);
                    },
                });
            }
        });

        $(document).on('change', '#store_id', event => {
            let $form_body = $('.shipment-create-wrap');
            Canopy.blockUI({
                target: $form_body,
                iconOnly: true,
                overlayColor: 'none'
            });

            $('#select-shipping-provider').load($('.btn-trigger-shipment').data('target') + '?view=true&store_id=' + $(event.currentTarget).val() + ' #select-shipping-provider > *', () => {
                Canopy.unblockUI($form_body);
                Canopy.initResources();
            });
        });

        $(document).on('change', '.shipment-form-weight', event => {
            let $form_body = $('.shipment-create-wrap');
            Canopy.blockUI({
                target: $form_body,
                iconOnly: true,
                overlayColor: 'none'
            });

            $('#select-shipping-provider').load($('.btn-trigger-shipment').data('target') + '?view=true&store_id=' + $('#store_id').val() + '&weight=' + $(event.currentTarget).val() + ' #select-shipping-provider > *', () => {
                Canopy.unblockUI($form_body);
                Canopy.initResources();
            });
        });

        $(document).on('click', '.table-shipping-select-options .clickable-row', event => {
            let _self = $(event.currentTarget);
            $('.input-hidden-shipping-method').val(_self.data('key'));
            $('.input-hidden-shipping-option').val(_self.data('option'));
            $('.input-show-shipping-method').val(_self.find('span.ws-nm').text());
        });

        $(document).on('click', '.btn-create-shipment', event => {
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
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        $('.btn-trigger-shipment').remove();
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

        $(document).on('click', '.btn-cancel-shipment', event => {
            event.preventDefault();
            $('#confirm-cancel-shipment-button').data('action', $(event.currentTarget).data('action'));
            $('#cancel-shipment-modal').modal('show');
        });

        $(document).on('click', '#confirm-cancel-shipment-button', event => {
            event.preventDefault();

            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('action'),
                success: res => {
                    if (!res.error) {
                        Canopy.showSuccess(res.message);
                        $('.carrier-status').addClass('carrier-status-' + res.data.status).text(res.data.status_text);
                        $('#cancel-shipment-modal').modal('hide');
                        $('#order-history-wrapper').load(window.location.href + ' #order-history-wrapper > *');
                        $('.shipment-actions-wrapper').remove();
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

        $(document).on('click', '.btn-close-shipment-panel', event => {
            event.preventDefault();
            $('.shipment-create-wrap').addClass('hidden');
        });

        $(document).on('click', '.btn-trigger-update-shipping-address', event => {
            event.preventDefault();
            $('#update-shipping-address-modal').modal('show');
        });

        $(document).on('click', '#confirm-update-shipping-address-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('.modal-content').find('form').prop('action'),
                data: _self.closest('.modal-content').find('form').serialize(),
                success: res => {
                    if (!res.error) {
                        Canopy.showSuccess(res.message);
                        $('#update-shipping-address-modal').modal('hide');
                        $('.shipment-address-box-1').html(res.data.line);
                        $('.text-infor-subdued.shipping-address-info').html(res.data.detail);
                        let $form_body = $('.shipment-create-wrap');
                        Canopy.blockUI({
                            target: $form_body,
                            iconOnly: true,
                            overlayColor: 'none'
                        });

                        $('#select-shipping-provider').load($('.btn-trigger-shipment').data('target') + '?view=true #select-shipping-provider > *', () => {
                            Canopy.unblockUI($form_body);
                            Canopy.initResources();
                        });
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

        $(document).on('click', '.btn-update-order', event => {
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

        $(document).on('click', '.btn-trigger-cancel-order', event => {
            event.preventDefault();
            $('#confirm-cancel-order-button').data('target', $(event.currentTarget).data('target'));
            $('#cancel-order-modal').modal('show');
        });

        $(document).on('click', '#confirm-cancel-order-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('target'),
                success: res => {
                    if (!res.error) {
                        Canopy.showSuccess(res.message);
                        $('#main-order-content').load(window.location.href + ' #main-order-content > *');
                        $('#cancel-order-modal').modal('hide');
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

        $(document).on('click', '.btn-trigger-confirm-payment', event => {
            event.preventDefault();
            $('#confirm-payment-order-button').data('target', $(event.currentTarget).data('target'));
            $('#confirm-payment-modal').modal('show');
        });

        $(document).on('click', '#confirm-payment-order-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);

            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('target'),
                success: res => {
                    if (!res.error) {
                        Canopy.showSuccess(res.message);
                        $('.page-content').load(window.location.href + ' .page-content > *');
                        $('#confirm-payment-modal').modal('hide');
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

        $(document).on('click', '.show-timeline-dropdown', event => {
            event.preventDefault();
            $($(event.currentTarget).data('target')).slideToggle();
            $(event.currentTarget).closest('.comment-log-item').toggleClass('bg-white');

        });

        $(document).on('keyup', '.input-sync-item', event => {
            let number = $(event.currentTarget).val();
            if (!number || isNaN(number)) {
                number = 0;
            }
            $(event.currentTarget).closest('.page-content').find($(event.currentTarget).data('target')).text(Canopy.numberFormat(parseFloat(number), 2));
        });

        $(document).on('click', '.btn-trigger-refund', event => {
            event.preventDefault();
            $('#confirm-refund-modal').modal('show');
        });

        $(document).on('change', '.j-refund-quantity', () => {
            let total_restock_items = 0;
            $.each($('.j-refund-quantity'), (index, el) => {
                let number = $(el).val();
                if (!number || isNaN(number)) {
                    number = 0;
                }
                total_restock_items += parseFloat(number);
            });

            $('.total-restock-items').text(total_restock_items);
        });

        $(document).on('click', '#confirm-refund-payment-button', event => {
            event.preventDefault();
            let _self = $(event.currentTarget);
            _self.addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('.modal-dialog').find('form').prop('action'),
                data: _self.closest('.modal-dialog').find('form').serialize(),
                success: res => {
                    if (!res.error) {
                        $('.page-content').load(window.location.href + ' .page-content > *');
                        Canopy.showSuccess(res.message);
                        _self.closest('.modal').modal('hide');
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
    new OrderAdminManagement().init();
});
