<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use HitNForget\Client;

echo Client::call(new \HitNForget\Requests\Request("http://localhost:8000/api/root?a=apple&b=2"
        , 'poSt'
        , [
            'adunitName' => 'lithim',
            'sizes' => [
                "280x1",
                "270x0",
            ],
        ]
        ,[
            'Connection' => 'keep-alive',
//            'Content-Type' => 'text/plain;charset=UTF-8'
        ]
    ))
    ->getRequestGenerated() . "";