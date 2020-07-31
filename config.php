<?php

return [
    'no_database' => false,
    'disabled' => false,
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
    'recipient_formatter' => null

];
