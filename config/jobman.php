<?php

return [

    'jobs' => [
        [
            'tag'   => 'hello-world',
            'class' => 'Jobman\Jobs\HelloWorldJob',
        ],
    ],

    'security' => [
        'allow_ip' => [
            '192.168.0.1',
            '10.0.0.1',
        ]
    ],

];
