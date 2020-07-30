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
    'allow_phones' => [],
    'text_template' => '{text}',
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
    ]
];
