<?php

return [
    'statuses' => [
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'delivering' => 'Delivering',
        'delivered'  => 'Delivered',
        'completed'  => 'Completed',
        'canceled'   => 'Canceled',
    ],
    'requesttypes' => [
        'single'    => 'Someone Else',
        'self'      => 'Self',
        'corporate' => 'Compny/Organisation',
    ],
    'name'                                      => 'Regions',
    'create'                                    => 'Create an region',
    'customer'                                  => [
        'messages' => [
            'cancel_error'   => 'The region is delivering or completed',
            'cancel_success' => 'You do cancel the region successful',
        ],
    ],
    'incomplete_region'                          => 'Incomplete regions',
    'region_id'                                  => 'Order ID',
    'product_id'                                => 'Product ID',
    'customer_label'                            => 'Customer',
    'talent_label'                                    => 'Talent',
    'amount'                                    => 'Amount',
    'tax_amount'                                => 'Tax Amount',
    'shipping_amount'                           => 'Shipping amount',
    'payment_method'                            => 'Payment method',
    'payment_status_label'                      => 'Payment status',
    'manage_regions'                             => 'Manage regions',
    'region_intro_description'                   => 'Once your store has regions, this is where you will process and track those regions.',
    'create_new_region'                          => 'Create a new region',
    'manage_incomplete_regions'                  => 'Manage incomplete regions',
    'incomplete_regions_intro_description'       => 'Empty regions.',
    'invoice_for_region'                         => 'Invoice for region',
    'created'                                   => 'Created',
    'invoice'                                   => 'Invoice',
    'return'                                    => 'Return',
    'total_refund_amount'                       => 'Total refund amount',
    'total_amount_can_be_refunded'              => 'Total amount can be refunded',
    'refund_reason'                             => 'Refund reason (optional)',
    'products'                                  => 'product(s)',
    'see_on_maps'                               => 'See on maps',
    'region'                                     => 'Order',
    'region_information'                         => 'Order information',
    'create_new_product'                        => 'Create a new product',
    'out_of_stock'                              => 'Out of stock',
    'products_available'                        => 'product(s) available',
    'no_products_found'                         => 'No products found!',
    'default'                                   => 'Default',
    'system'                                    => 'System',
    'new_region_from'                            => 'New region :region_id from :customer',
    'confirmation_email_was_sent_to_customer'   => 'The email confirmation was sent to customer',
    'confirmation_email_was_sent_to_talent'   => 'The email confirmation was sent to talent',
    'address_name_required'                     => 'The name field is required.',
    'address_phone_required'                    => 'The phone field is required.',
    'address_email_required'                    => 'The email field is required.',
    'address_email_unique'                      => 'The customer with that email is existed, please choose other email or login with this email!',
    'address_state_required'                    => 'The state field is required.',
    'address_city_required'                     => 'The city field is required.',
    'address_address_required'                  => 'The address field is required.',
    'create_region_from_payment_page'            => 'Order was created from checkout page',
    'region_was_verified_by'                     => 'Order was verified by %user_name%',
    'new_region'                                 => 'New region :region_id',
    'payment_was_confirmed_by'                  => 'Payment was confirmed (amount :money) by %user_name%',
    'edit_region'                                => 'Edit region :code',
    'confirm_region_success'                     => 'Confirm region successfully!',
    'error_when_sending_email'                  => 'There is an error when sending email',
    'sent_confirmation_email_success'           => 'Sent confirmation email successfully!',
    'region_was_sent_to_shipping_team'           => 'Order was sent to shipping team',
    'by_username'                               => 'by %user_name%',
    'shipping_was_created_from'                 => 'Shipping was created from region %region_id%',
    'shipping_was_canceled_success'             => 'Shipping was cancelled successfully!',
    'shipping_was_canceled_by'                  => 'Shipping was cancelled by %user_name%',
    'update_shipping_address_success'           => 'Update shipping address successfully!',
    'region_was_canceled_by'                     => 'Order was cancelled by %user_name%',
    'confirm_payment_success'                   => 'Confirm payment successfully!',
    'refund_amount_invalid'                     => 'Refund amount must be lower or equal :price',
    'number_of_products_invalid'                => 'Number of products refund is not valid!',
    'cannot_found_payment_for_this_region'       => 'Cannot found payment for this region!',
    'refund_success_with_price'                 => 'Refund success :price',
    'refund_success'                            => 'Refund successfully!',
    'region_is_not_existed'                      => 'Order is not existed!',
    'reregion'                                   => 'Reregion',
    'sent_email_incomplete_region_success'       => 'Sent email to remind about incomplete region successfully!',
    'applied_coupon_success'                    => 'Applied coupon ":code" successfully!',
    'new_region_notice'                          => 'You have <span class="bold">:count</span> New Order(s)',
    'view_all'                                  => 'View all',
    'cancel_region'                              => 'Cancel region',
    'region_was_canceled_at'                     => 'Order was canceled at',
    'completed'                                 => 'Completed',
    'uncompleted'                               => 'Uncompleted',
    'sku'                                       => 'SKU',
    'shipping'                                  => 'Shipping',
    'warehouse'                                 => 'Warehouse',
    'sub_amount'                                => 'Sub amount',
    'discount'                                  => 'Discount',
    'coupon_code'                               => 'Coupon code: ":code"',
    'shipping_fee'                              => 'Delivery fee',
    'tax'                                       => 'Tax',
    'total_amount'                              => 'Total amount',
    'paid_amount'                               => 'Paid amount',
    'refunded_amount'                           => 'Refunded amount',
    'amount_received'                           => 'The amount actually received',
    'download_invoice'                          => 'Download invoice',
    'note'                                      => 'Note',
    'add_note'                                  => 'Add note...',
    'save'                                      => 'Save',
    'region_was_confirmed'                       => 'Order was confirmed',
    'confirm_region'                             => 'Confirm region',
    'confirm'                                   => 'Confirm',
    'region_was_canceled'                        => 'Order was canceled',
    'pending_payment'                           => 'Pending payment',
    'payment_was_accepted'                      => 'Payment :money was accepted',
    'payment_was_refunded'                      => 'Payment was refunded',
    'confirm_payment'                           => 'Confirm payment',
    'refund'                                    => 'Refund',
    'all_products_are_not_delivered'            => 'All products are not delivered',
    'delivery'                                  => 'Delivery',
    'history'                                   => 'History',
    'region_number'                              => 'Order number',
    'description'                               => 'Description',
    'from'                                      => 'from',
    'status'                                    => 'Status',
    'successfully'                              => 'Successfully',
    'transaction_type'                          => 'Transaction\'s type',
    'staff'                                     => 'Staff',
    'refund_date'                               => 'Refund date',
    'n_a'                                       => 'N\A',
    'payment_date'                              => 'Payment date',
    'payment_gateway'                           => 'Payment gateway',
    'transaction_amount'                        => 'Transaction amount',
    'resend'                                    => 'Resend',
    'regions'                                    => 'Regions',
    'shipping_address'                          => 'Shipping address',
    'default_store'                             => 'Default store',
    'update_address'                            => 'Update address',
    'cancel'                                    => 'Cancel',
    'have_an_account_already'                   => 'Have an account already',
    'dont_have_an_account_yet'                  => 'Don\'t have an account yet',
    'mark_payment_as_confirmed'                 => 'Mark <span>:method</span> as confirmed',
    'resend_region_confirmation'                 => 'Resend region confirmation',
    'resend_region_confirmation_description'     => 'Confirmation email will be sent to <strong>:email</strong>?',
    'send'                                      => 'Send',
    'update'                                    => 'Update',
    'cancel_shipping_confirmation'              => 'Cancel shipping confirmation?',
    'cancel_shipping_confirmation_description'  => 'Cancel shipping confirmation?',
    'cancel_region_confirmation'                 => 'Cancel region confirmation?',
    'cancel_region_confirmation_description'     => 'Are you sure you want to cancel this region? This action cannot rollback',
    'confirm_payment_confirmation_description'  => 'Processed by <strong>:method</strong>. Did you receive payment outside the system? This payment won\'t be saved into system and cannot be refunded',
    'save_note'                                 => 'Save note',
    'region_note'                                => 'Order note',
    'region_note_placeholder'                    => 'Note about region, ex: time or shipping instruction.',
    'region_amount'                              => 'Order amount',
    'additional_information'                    => 'Additional information',
    'notice_about_incomplete_region'             => 'Notice about incomplete region',
    'notice_about_incomplete_region_description' => 'Remind email about uncompleted region will be send to <strong>:email</strong>?',
    'incomplete_region_description_1'            => 'An incomplete region is when a potential customer places items in their shopping cart, and goes all the way through to the payment page, but then doesn\'t complete the transaction.',
    'incomplete_region_description_2'            => 'If you have contacted customers and they want to continue buying, you can help them complete their region by following the link:',
    'send_an_email_to_recover_this_region'       => 'Send an email to customer to recover this region',
    'see_maps' => 'See maps',

];
