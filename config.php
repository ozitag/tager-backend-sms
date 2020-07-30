<?php

return [
    'debug' => false,
    'no_database' => false,
    'service' => [
        'id' => 'rocketsms',
        'params' => [
            'apiKey' => 'XXXXXX'
        ]
    ],
    'valid_phones' => [],
    'text_template' => '{text}',
    'templates' => [
        'newOrder' => [
            'name' => 'New Order',
            'text' => '.....',
            'templateParams' => [
                'name' => 'Name',
                'orderId' => 'Order Id'
            ],
            'recipients' => []
        ]
    ]
];
