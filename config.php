<?php

return [
    'no_database' => false,
    'service' => [
        'id' => 'rocketsms',
        'params' => [
            'apiKey' => 'XXXXXX'
        ]
    ],
    'allow_phones' => [],
    'message_template' => '{text}',
    'templates' => [
        'newOrder' => [
            'name' => 'New Order',
            'templateParams' => [
                'name' => 'Name',
                'orderId' => 'Order Id'
            ],
            'recipients' => [],
            'value' => '.....',
        ]
    ],
    'recipient_formatter' => function ($phone) {
        return $phone;
    }
];
