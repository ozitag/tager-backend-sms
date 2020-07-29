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
    'templates' => [
        'newOrder' => [
            'label' => 'New Order',
            'templateParams' => [
                'name' => 'Name',
                'orderId' => 'Order Id'
            ]
        ]
    ]
];
