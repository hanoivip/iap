<?php
return [
    'htv' => [
        'id' => 'htv',
        'client_id' => 'Ae4g3yM-SqsVhSG2jMbmL3IoLr4NOyOt3E-uf6BJ0ZUQUf1y6Od3GhyaOZhqc9z9PK_E9LgAvTmCy9-W',
        'secret' => 'ELCU7oyASU1hD0Eimp83jksCnsMUR6a9xPjbbPY6zYKXg8QSushEgcvwQB29yP85XEusbCeyvs28ikOh',
        'settings' => [
            'mode' => 'live',
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal-htv.log',
            'log.LogLevel' => 'ERROR'
        ],
        'business_account' => 'game.oh.vn-facilitator@gmail.com',
    ],
    'dev' => [
        'id' => 'dev',
        'client_id' => 'AUzS3sJcWrTEaifzvuZ36YOZ45VJu06T-sVtM7JKjZHq4kaOiJXjS-_ozs0vk44kkH7hQ9OuLYEQF04j',
        'secret' => 'EObDBerF8s-aLpvuASZfiErPCbkKKwQeRmd6rBc49v7JeJG5mgU02GyAZXI93kfOkJo9xS_s8Z0st7RI',
        'settings' => [
            'mode' => 'sandbox',
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal-dev.log',
            'log.LogLevel' => 'INFO'
        ],
        'business_account' => 'sb-g81rk753295@business.example.com',
    ],
    'ddd2' => [
        'id' => 'ddd2',
        'client_id' => 'Ad9JgGgKJc66ZRUZqWzxCKaMGVBo7OXBeaaga5uCwTJOFVIO8ueRCFZLiJzwJth4OtCRw9XjwO8pVD1E',
        'secret' => 'EDJNv_NJV39VD1YqLMUZDDpHH3vp4C07Jp3mGyDm-shR3AM3f65nrTbTfdrloU-fQQT48GMY6Ju_01my',
        'settings' => [
            'mode' => 'live',
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal-ddd2.log',
            'log.LogLevel' => 'ERROR'
        ],
        'business_account' => 'game.oh.vn-facilitator@gmail.com',
    ]
];