<?php

return [
    'name'        => 'Oplly',
    'description' => 'Config email templates for Oplly',
    'templates'   => [
        'register_confirm'      => [
            'title'       => 'Signup Confirmation email',
            'description' => 'Welcome email after user registration',
            'subject'     => 'Welcome to Oplly',
            'can_off'     => false,
        ],

        'talent_register_confirm'      => [
            'title'       => 'Talent Signup Confirmation email',
            'description' => 'Welcome email after talent registration',
            'subject'     => 'Welcome to Oplly',
            'can_off'     => false,
        ],
        'talent_refer_confirm'      => [
            'title'       => 'Talent Referral Invitation email',
            'description' => 'Invitation email talent referral ',
            'subject'     => 'Welcome to Oplly',
            'can_off'     => false,
        ],
        'talent_approved_confirm'  => [
            'title'       => 'Approved Talent onboarding email',
            'description' => 'Sent to talent when approved',
            'subject'     => 'Your talent account is now live',
            'can_off'     => false,
        ],

        /*         'talent_first_time_pass'      => [
            'title'       => 'Approved Talent onboarding email',
            'description' => 'Sent to talent when approved',
            'subject'     => 'Your talent account is now live',
            'can_off'     => false,
        ], */

        'talent_accept_request'      => [
            'title'       => 'Talent Accepted Request',
            'description' => 'Talent has accepted request',
            'subject'     => 'Your Request was accepted',
            'can_off'     => false,
        ],

        'talent_reject_request'      => [
            'title'       => 'Talent rejected Request',
            'description' => 'Talent has rejected request',
            'subject'     => 'Your Request was rejected',
            'can_off'     => false,
        ],

        'talent_new_request'      => [
            'title'       => 'New Request for talent',
            'description' => 'Sent to talent when new request is made',
            'subject'     => 'New Request from {{ customer_name }}',
            'can_off'     => false,
        ],

        'talent_confirm_delivery'      => [
            'title'       => 'Talent completed the request',
            'description' => 'Sent to customer once the video is available',
            'subject'     => 'Your request is complete',
            'can_off'     => false,
        ],

        'customer_new_order'      => [
            'title'       => 'plugins/ecommerce::email.customer_new_order_title',
            'description' => 'plugins/ecommerce::email.customer_new_order_description',
            'subject'     => 'New Request {{ order_id }}',
            'can_off'     => true,
        ],
        'customer_cancel_order'   => [
            'title'       => 'plugins/ecommerce::email.order_cancellation_title',
            'description' => 'plugins/ecommerce::email.order_cancellation_description',
            'subject'     => 'Request cancelled {{ order_id }}',
            'can_off'     => true,
        ],

        'customer_request_review'   => [
            'title'       => 'Rate your talent',
            'description' => 'Sent when customer has received the video',
            'subject'     => 'Rate your talent. Share the experience',
            'can_off'     => true,
        ],

        'customer_delivery_order' => [
            'title'       => 'plugins/ecommerce::email.delivery_confirmation_title',
            'description' => 'plugins/ecommerce::email.delivery_confirmation_description',
            'subject'     => 'Request delivering {{ order_id }}',
            'can_off'     => true,
        ],
        'admin_new_order'         => [
            'title'       => 'plugins/ecommerce::email.admin_new_order_title',
            'description' => 'plugins/ecommerce::email.admin_new_order_description',
            'subject'     => 'New Request {{ order_id }}',
            'can_off'     => true,
        ],
        'order_confirm'           => [
            'title'       => 'plugins/ecommerce::email.order_confirmation_title',
            'description' => 'plugins/ecommerce::email.order_confirmation_description',
            'subject'     => 'Request confirmed {{ order_id }}',
            'can_off'     => true,
        ],
        'order_confirm_payment'   => [
            'title'       => 'plugins/ecommerce::email.payment_confirmation_title',
            'description' => 'plugins/ecommerce::email.payment_confirmation_description',
            'subject'     => 'Payment for the request {{ order_id }} was confirmed',
            'can_off'     => true,
        ],
        'order_recover'           => [
            'title'       => 'plugins/ecommerce::email.order_recover_title',
            'description' => 'plugins/ecommerce::email.order_recover_description',
            'subject'     => 'Incomplete Request',
            'can_off'     => true,
        ],

        'notify_when_back'           => [
            'title'       => 'Notify when back',
            'description' => 'Sends notification when talent is available',
            'subject'     => 'Your talent is available',
            'can_off'     => false,
        ],
    ],
    'variables'   => [
        'store_address'    => 'plugins/ecommerce::ecommerce.store_address',
        'store_phone'      => 'plugins/ecommerce::ecommerce.store_phone',
        'order_id'         => 'plugins/ecommerce::ecommerce.order_id',
        'request_id'       => 'Request ID',
        'order_token'      => 'plugins/ecommerce::ecommerce.order_token',
        'talent_name'      => 'Talent Name',
        'talent_slug'      => 'Talent URL slug',
        'customer_name'    => 'plugins/ecommerce::ecommerce.customer_name',
        'customer_email'   => 'plugins/ecommerce::ecommerce.customer_email',
        'customer_phone'   => 'plugins/ecommerce::ecommerce.customer_phone',
        'customer_address' => 'plugins/ecommerce::ecommerce.customer_address',
        'product_list'     => 'plugins/ecommerce::ecommerce.product_list',
        'payment_detail'   => 'plugins/ecommerce::ecommerce.payment_detail',
        'shipping_method'  => 'plugins/ecommerce::ecommerce.shipping_method',
        'payment_method'   => 'plugins/ecommerce::ecommerce.payment_method',
    ],
];
