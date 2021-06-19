<?php

return [
    'standalone' => false,
    'default_client' => 'clientdev',
    'methods' => [
        'paypal' => ['id' => 'dev', 'clazz' => '\Hanoivip\Iap\Services\Paypal']
    ],
];